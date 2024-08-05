<?php

namespace App\Controller;

use App\Entity\DetailVente;
use App\Entity\MouvementStock;
use App\Entity\PaiementCredit;
use App\Entity\Vente;
use App\Repository\ClientRepository;
use App\Repository\MagasinRepository;
use App\Repository\PaiementCreditRepository;
use App\Repository\ProduitsRepository;
use App\Repository\StockRepository;
use App\Repository\UnitesPRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/vente', name: 'app_vente')]
class VenteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/validateVente', name: 'validateVente')]
    public function validateVente( Request $request, MagasinRepository $mag, ProduitsRepository $p, UnitesPRepository $u, StockRepository $s )
    {
        $data = json_decode($request->getContent()) ;
        $vente = $data->vente ;
        $detailVente = $data->produit ;
        $newVente = new Vente() ;
        $newVente->setDate(new DateTime()) ;
        $newVente->setMagasin($mag->find(1)) ;
        $newVente->setMontanttotal($vente->montanttotal) ;
        $newVente->setModePaiement($vente->modePaiement) ;
        if(isset($vente->refPaiement)){
            $newVente->setRefPaiement($vente->refPaiement) ;
        }
        $entityManager = $this->entityManager ;
        $entityManager->persist($newVente) ;
        $entityManager->flush() ;

        

        for ($i=0; $i < count($detailVente) ; $i++) { 

            $newDetailVente = new DetailVente() ;
            $newDetailVente->setVente($newVente) ;
            $produitInsert = $p->find($detailVente[$i]->produits->id) ;
            $newDetailVente->setProduit($produitInsert) ;
            $newDetailVente->setUnite($u->find($detailVente[$i]->id)) ;
            $newDetailVente->setQte($detailVente[$i]->valeur) ;
            $newDetailVente->setPrix($detailVente[$i]->prix) ;
            $newDetailVente->setSuppr(0) ;

            $entityManager = $this->entityManager ;
            $entityManager->persist($newDetailVente) ;
            $entityManager->flush() ;

            $stock = $s->findOneByProduitAndUniteAndMagasin($produitInsert, $u->find($detailVente[$i]->id), $mag->find(1)) ;
            $stock->setQte($stock->getQte() - $detailVente[$i]->valeur) ;
            $entityManager = $this->entityManager ;
            $entityManager->persist($stock) ;
            $entityManager->flush() ;
            $mouvementStk = new MouvementStock() ;
            $mouvementStk->setDate(new DateTime()) ;
            $mouvementStk->setProduits($produitInsert) ;
            $mouvementStk->setEmplacementoriginMagasin($mag->find(1)) ;
            $mouvementStk->setQte($detailVente[$i]->valeur) ;
            $mouvementStk->setDescription('VENTE') ;
            $mouvementStk->setUniteP($u->find($detailVente[$i]->id)) ;
            $entityManager = $this->entityManager ;
            $entityManager->persist($mouvementStk) ;
            $entityManager->flush() ;
        }

        return $this->json(['Produits' => $u->findBySuppr(0)], 200, [], ['groups' => 'stock:read']) ;
    }

    #[Route('/validateVenteCredit', name: 'validateVenteCredit')]
    public function validateVenteCredit( Request $request, MagasinRepository $mag, ProduitsRepository $p, UnitesPRepository $u, ClientRepository $cli, PaiementCreditRepository $paiementC, StockRepository $s )
    {
        $data = json_decode($request->getContent()) ;
        $vente = $data->vente ;
        $detailVente = $data->produit ;
        $newVente = new Vente() ;
        $newVente->setClient($cli->find($vente->client->id)) ;
        $newVente->setDate(new DateTime()) ;
        $newVente->setMagasin($mag->find(1)) ;
        $newVente->setMontanttotal($vente->montanttotal) ;
        $newVente->setModePaiement($vente->modePaiement) ;
        if(isset($vente->refPaiement)){
            $newVente->setRefPaiement($vente->refPaiement) ;
        }
        $entityManager = $this->entityManager ;
        $entityManager->persist($newVente) ;
        $entityManager->flush() ;

        for ($i=0; $i < count($detailVente) ; $i++) {
            $newDetailVente = new DetailVente() ;
            $newDetailVente->setVente($newVente) ;
            $produitInsert = $p->find($detailVente[$i]->produits->id) ;
            $newDetailVente->setProduit($produitInsert) ;
            $newDetailVente->setUnite($u->find($detailVente[$i]->id)) ;
            $newDetailVente->setQte($detailVente[$i]->valeur) ;
            $newDetailVente->setPrix($detailVente[$i]->prix) ;
            $newDetailVente->setSuppr(0) ;

            $entityManager = $this->entityManager ;
            $entityManager->persist($newDetailVente) ;
            $entityManager->flush() ;
        }

        $client = $cli->find($vente->client->id) ;
        $client->setCompte($client->getCompte() + $vente->montanttotal - $data->montantEncaisse) ;
        $entityManager = $this->entityManager ;
        $entityManager->persist($client) ;
        $entityManager->flush() ;

        $PaiementCredit = new PaiementCredit() ;
        $PaiementCredit->setVente($newVente) ;
        $PaiementCredit->setClient($client) ;
        $PaiementCredit->setMontantPaye($data->montantEncaisse) ;
        $PaiementCredit->setResteAPaye($vente->montanttotal - $data->montantEncaisse) ;
        $PaiementCredit->setDate(new DateTime()) ;
        $entityManager = $this->entityManager ;
        $entityManager->persist($PaiementCredit) ;
        $entityManager->flush() ;

        $stock = $s->findOneByProduitAndUniteAndMagasin($produitInsert, $u->find($detailVente[$i]->id), $mag->find(1)) ;
        $stock->setQte($stock->getQte() - $detailVente[$i]->valeur) ;
        $entityManager = $this->entityManager ;
        $entityManager->persist($stock) ;
        $entityManager->flush() ;

        $mouvementStk = new MouvementStock() ;
        $mouvementStk->setDate(new DateTime()) ;
        $mouvementStk->setEmplacementoriginMagasin($mag->find(1)) ;
        $mouvementStk->setQte($detailVente[$i]->valeur) ;
        $mouvementStk->setDescription('VENTE') ;
        $mouvementStk->setUniteP($u->find($detailVente[$i]->id)) ;
        $entityManager = $this->entityManager ;
        $entityManager->persist($mouvementStk) ;
        $entityManager->flush() ;

        return $this->json(['Produits' => $u->findBySuppr(0)], 200, [], ['groups' => 'stock:read']) ;

    }

    
}
