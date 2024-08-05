<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Entity\Stock;
use App\Entity\UnitesP;
use App\Repository\FamilleRepository;
use App\Repository\ProduitsRepository;
use App\Repository\UnitesPRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/stock', name: 'app_stock')]

class StockController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/getProduits', name: 'getProduit', methods: ['GET'])]
    public function getProduit(ProduitsRepository $p)
    {
        return $this->json(['Produits' => $p->findBySuppr(0)], 200, [], ['groups' => 'forStk:read']) ;
    }

    #[Route('/getProduitStock', name: 'getProduitStock', methods: ['GET'])]
    public function getProduitStock(UnitesPRepository $u)
    {
        return $this->json(['Produits' => $u->findBySuppr(0)], 200, [], ['groups' => 'stock:read']) ;
    }

    #[Route('/createProduct', name: 'createProduct', methods: ['POST'])]
    public function createProduct(Request $request, FamilleRepository $famille, ProduitsRepository $p)
    {
        $data = json_decode($request->getContent()) ;

        $familleOfNewP = $famille->findOneByLibelle($data->Produit->famille->libelle) ;

        $newProduit = new Produits() ;
        $newProduit->setNom($data->Produit->nom) ;
        $newProduit->setDescription($data->Produit->description) ;
        $newProduit->setSeuil($data->Produit->seuil) ;
        $newProduit->setSuppr(0) ;

        $newProduit->setFamille($familleOfNewP) ;
        $entityManager = $this->entityManager;
        $entityManager->persist($newProduit);
        $entityManager->flush();

        $unite = $data->Produit->unite ;

        for ($i=0; $i < count($unite); $i++) {
            $newUnite = new UnitesP() ;
            $newUnite->setNom($unite[$i]->nom) ;
            $newUnite->setPrix($unite[$i]->prix) ;
            $newUnite->setValeur($unite[$i]->valeur) ;
            $newUnite->setProduits($newProduit) ;
            $newUnite->setSuppr(0) ;

            $stock = new Stock() ;
            
            $stock->setProduit($newProduit) ;
            $stock->setUnite($newUnite) ;
            $stock->setQte(0) ;

            $entityManager = $this->entityManager;
            $entityManager->persist($newUnite);
            $entityManager->flush();
        }

        return $this->json(['Produits' => $p->findBySuppr(0)], 200, [], ['groups' => 'produit:read']) ;
    }

    #[Route('/updateProduit', name: 'updateProduit', methods: ['POST'])]
    public function updateProduit( Request $request, ProduitsRepository $p, FamilleRepository $famille, UnitesPRepository $unite)
    {
        $data = json_decode($request->getContent()) ;

        $familleOfNewP = $famille->findOneByLibelle($data->Produit->famille->libelle) ;

        $prodToUpdate = $p->find($data->Produit->id) ;
        
        $prodToUpdate->setNom($data->Produit->nom) ;
        $prodToUpdate->setDescription($data->Produit->description) ;
        $prodToUpdate->setSeuil($data->Produit->seuil) ;
        $prodToUpdate->setSuppr(0) ;
        $prodToUpdate->setFamille($familleOfNewP) ;
        $entityManager = $this->entityManager;
        $entityManager->persist($prodToUpdate);
        $entityManager->flush();

        $uniteData = $data->Produit->unite ;

        for ($i=0; $i < count($uniteData); $i++) {

            if($data->Produit->unite[$i]->id == 0){
                $newUnite = new UnitesP() ;
                $newUnite->setNom($uniteData[$i]->nom) ;
                $newUnite->setPrix($uniteData[$i]->prix) ;
                $newUnite->setValeur($uniteData[$i]->valeur) ;
                $newUnite->setProduits($prodToUpdate) ;
                $newUnite->setSuppr(0) ;

                $entityManager = $this->entityManager;
                $entityManager->persist($newUnite);
                $entityManager->flush();
            }else{
                $uniteToUpdate = $unite->find($data->Produit->unite[0]->id) ;
                $uniteToUpdate->setNom($uniteData[$i]->nom) ;
                $uniteToUpdate->setPrix($uniteData[$i]->prix) ;
                $uniteToUpdate->setValeur($uniteData[$i]->valeur) ;
                $uniteToUpdate->setProduits($prodToUpdate) ;
                $entityManager = $this->entityManager;
                $entityManager->persist($uniteToUpdate);
                $entityManager->flush();
            }
        }
        return $this->json(['Produits' => $p->findBySuppr(0)], 200, [], ['groups' => 'produit:read']) ;
    }

    #[Route('/deleteProduit', name: 'deleteProduit', methods: ['POST'])]
    public function deleteProduit( Request $request ,ProduitsRepository $p)
    {
        $data =json_decode($request->getContent()) ;
        $produitToSuppr = $p->find($data->Produit->id) ;
        $produitToSuppr->setSuppr(1) ;
        $entityManager = $this->entityManager;
        $entityManager->persist($produitToSuppr);
        $entityManager->flush();

        return $this->json(['Produits' => $p->findBySuppr(0)], 200, [], ['groups' => 'produit:read']) ;
    }
}
