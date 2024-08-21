<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\MouvementStock;
use App\Entity\PaiementFrs;
use App\Entity\Stock;
use App\Repository\DepotRepository;
use App\Repository\FournisseurRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitsRepository;
use App\Repository\StockRepository;
use App\Repository\UnitesPRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/mouvement/stock', name: 'app_mouvement_stock')]

class MouvementStockController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/validateAppro', name: 'validateAppro', methods:['POST'])]
    public function validateAppro( Request $request, ProduitsRepository $p, UnitesPRepository $u, FournisseurRepository $f, DepotRepository $d, MagasinRepository $m, StockRepository $s)
    {
        $data = json_decode($request->getContent()) ;
        $fournisseur = $f->find($data->detail[0]->commande->fournisseur->id) ;
        $MontantTotalAchat= 0 ;
        $montantTot = 0 ;
        $achat = new Achat() ;
        $achat->setDate(new DateTime()) ;
        $achat->setSuppr(0) ;
        $achat->setFournisseur($fournisseur) ;
        $achat->setPaid(0) ;

        for($i=0; $i < count($data->detail); $i++){
            $produit = $p->find($data->detail[$i]->produit->id) ;
            for ($j=0; $j < count($data->detail[$i]->produit->unite) ; $j++) { 
                if(($data->detail[$i]->produit->unite[$j]->valeur)*($data->detail[$i]->produit->unite[$j]->prix)>0){
                    $montantTot = $montantTot + ($data->detail[$i]->produit->unite[$j]->valeur)*($data->detail[$i]->produit->unite[$j]->prix) ;
                }
            }
        }

        $achat->setMontant($montantTot) ;

        $entityManager = $this->entityManager ;
        $entityManager->persist($achat) ;
        $entityManager->flush() ;

        //MOUVEMENT STOCK
        for ($i=0; $i < count($data->detail); $i++) {
            $produit = $p->find($data->detail[$i]->produit->id) ;
            $fournisseur = $f->find($data->detail[$i]->commande->fournisseur->id) ;
            $fournisseur->setcompte( $fournisseur->getCompte()+$data->detail[$i]->prix ) ;
            $entityManager = $this->entityManager ;
            $entityManager->persist($fournisseur) ;
            $entityManager->flush() ;
            if(!empty($data->detail[$i]->commande->depot->id) && $data->detail[$i]->commande->depot->nom!='' ){
                $depot = $d->find($data->detail[$i]->commande->depot->id) ;
            }

            if(!empty($data->detail[$i]->commande->magasin->id) && $data->detail[$i]->commande->magasin->nom!='' ){
                $magasin = $m->find($data->detail[$i]->commande->magasin->id) ;
            }

            if( count($data->detail[$i]->produit->unite)>0 ){ 
                for ($j=0; $j < count($data->detail[$i]->produit->unite) ; $j++) {
                    $uniteSelectionner = $u->find($data->detail[$i]->produit->unite[$j]->id) ;

                    $mvtStk = new MouvementStock() ;
                    $mvtStk->setProduits($produit) ;
                    $mvtStk->setAchat($achat) ;
                    $mvtStk->setDate(new DateTime()) ;
                    $mvtStk->setDescription('Approvisionnement '.$data->detail[$i]->produit->nom.'( En'.$data->detail[$i]->produit->unite[$j]->nom.')') ;
                    $mvtStk->setFournisseur($fournisseur) ;
                    $mvtStk->setQte($data->detail[$i]->produit->unite[$j]->valeur) ;
                    $mvtStk->setPrixAchat(($data->detail[$i]->produit->unite[$j]->valeur)*($data->detail[$i]->produit->unite[$j]->prix)) ;
                    $mvtStk->setUniteP($uniteSelectionner) ;
                    $MontantTotalAchat = $MontantTotalAchat + ($data->detail[$i]->produit->unite[$j]->valeur)*($data->detail[$i]->produit->unite[$j]->prix) ;

                    if(($data->detail[$i]->produit->unite[$j]->valeur)*($data->detail[$i]->produit->unite[$j]->prix)>0){
                        
                        //STOCK
                        if(isset($depot)){
                            $stock = $s->findOneByProduitAndUniteAndDepot($produit, $uniteSelectionner, $depot) ;
                        }

                        if(isset($magasin)){
                            $stock = $s->findOneByProduitAndUniteAndMagasin($produit, $uniteSelectionner, $magasin) ;
                        }

                        if(!empty($stock)){
                            $stock->setProduit($produit) ;
                            $stock->setQte(($data->detail[$i]->produit->unite[$j]->valeur)+$stock->getQte()) ;
                            $stock->setQteTheorique(($data->detail[$i]->produit->unite[$j]->valeur)+$stock->getQteTheorique()) ;
                        }else{
                            $stock = new Stock() ;
                            $stock->setProduit($produit) ;
                            $stock->setQte($data->detail[$i]->produit->unite[$j]->valeur) ;
                            $stock->setQteTheorique($data->detail[$i]->produit->unite[$j]->valeur) ;
                        }

                        $stock->setUnite($uniteSelectionner) ;
                        $entityManager = $this->entityManager ;
                        $entityManager->persist($stock) ;
                        $entityManager->flush() ;

                        if(isset($depot)){
                            $mvtStk->setDestinationDepot($depot) ;
                            $stock->setDepot($depot) ;
                            $uniteSelectionner->setStockDepot($stock) ;
                        }

                        if(isset($magasin)){
                            $mvtStk->setDestinationMagasin($magasin) ;
                            $stock->setMagasin($magasin) ;
                            $uniteSelectionner->setStockMagasin($stock) ;
                        }

                        $entityManager = $this->entityManager ;
                        $entityManager->persist($uniteSelectionner) ;
                        $entityManager->flush() ;

                        $entityManager = $this->entityManager ;
                        $entityManager->persist($mvtStk) ;
                        $entityManager->flush() ;
                    }                    
                }
            }
        }

        $paiementAchat = new PaiementFrs() ;
        $paiementAchat->setAchat($achat) ;
        $paiementAchat->setDate(new DateTime) ;
        $paiementAchat->setMontantP(0) ;
        $paiementAchat->setReste($MontantTotalAchat) ;

        $entityManager = $this->entityManager ;
        $entityManager->persist($paiementAchat) ;
        $entityManager->flush() ;

        return $this->json($stock, 200, [], ['groups' => 'produit:read']) ;
    }
}
