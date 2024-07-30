<?php

namespace App\Controller;

use App\Entity\MouvementStock;
use App\Entity\Stock;
use App\Repository\DepotRepository;
use App\Repository\FournisseurRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitsRepository;
use App\Repository\StockRepository;
use App\Repository\UnitesPRepository;
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

        //MOUVEMENT STOCK
        for ($i=0; $i < count($data->detail); $i++) { 
            $produit = $p->find($data->detail[$i]->produit->id) ;
            $fournisseur = $f->find($data->detail[$i]->commande->fournisseur->id) ;

            if(!empty($data->detail[$i]->commande->depot->id) && $data->detail[$i]->commande->depot->nom!='' ){
                $depot = $d->find($data->detail[$i]->commande->depot->id) ;
            }

            if(!empty($data->detail[$i]->commande->magasin->id) && $data->detail[$i]->commande->magasin->nom!='' ){
                $magasin = $m->find($data->detail[$i]->commande->magasin->id) ;
            }

            if( count($data->detail[$i]->produit->unite)>0 ){   
                for ($j=0; $j < count($data->detail[$i]->produit->unite) ; $j++) {

                    $unite = $u->find($data->detail[$i]->produit->unite[$j]->id) ;
                    $uniteSelectionner = $u->find($data->detail[$i]->produit->unite[$j]->id) ;

                    $mvtStk = new MouvementStock() ;
                    $mvtStk->setProduits($produit) ;
                    $mvtStk->setDate(new \DateTime()) ;
                    $mvtStk->setDescription('Approvisionnement '.$data->detail[$i]->produit->nom.'( En'.$data->detail[$i]->produit->unite[$j]->nom.')') ;
                    $mvtStk->setFournisseur($fournisseur) ;
                    $mvtStk->setQte($data->detail[$i]->produit->unite[$j]->valeur) ;
                    $mvtStk->setPrixAchat(($data->detail[$i]->produit->unite[$j]->valeur)*($data->detail[$i]->produit->unite[$j]->prix)) ;
                    $mvtStk->setUniteP($uniteSelectionner) ;

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
                    }else{
                        $stock = new Stock() ;
                        $stock->setProduit($produit) ;
                        $stock->setQte($data->detail[$i]->produit->unite[$j]->valeur) ;
                    }

                    $stock->setUnite($uniteSelectionner) ;

                    if(isset($depot)){
                        $mvtStk->setDestinationDepot($depot) ;
                        $stock->setDepot($depot) ;
                        $unite->setStockDepot($stock) ;
                    }

                    if(isset($magasin)){
                        $mvtStk->setDestinationMagasin($magasin) ;
                        $stock->setMagasin($magasin) ;
                        $unite->setStockMagasin($stock) ;
                    }

                    

                    if(($data->detail[$i]->produit->unite[$j]->valeur)*($data->detail[$i]->produit->unite[$j]->prix)>0){
                        $entityManager = $this->entityManager ;
                        $entityManager->persist($mvtStk) ;
                        $entityManager->persist($stock) ;
                        $entityManager->persist($unite) ;
                        $entityManager->flush() ;
                    }                    
                }
            }
        }
        return $this->json($stock, 200, [], ['groups' => 'produit:read']) ;
    }
}
