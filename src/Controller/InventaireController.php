<?php

namespace App\Controller;

use App\Entity\Inventaire;
use App\Entity\MouvementStock;
use App\Repository\DepotRepository;
use App\Repository\InventaireRepository;
use App\Repository\MagasinRepository;
use App\Repository\ProduitsRepository;
use App\Repository\StockRepository;
use App\Repository\UnitesPRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/inventaire', name: 'app_inventaire')]
class InventaireController extends AbstractController
{
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/getInventaire', name: 'getInventaire')]
    public function getInventaire( InventaireRepository $i )
    {
        return $this->json([ 'Inventaires' => $i->findAll()], 200, []) ;
    }

    #[Route('/validate', name: 'validate')]
    public function validate( Request $request, MagasinRepository $mag, DepotRepository $dp, ProduitsRepository $p, UnitesPRepository $u, StockRepository $stk )
    {
        $data = json_decode($request->getContent()) ;
        $newInventaire = new Inventaire() ;
        $newInventaire->setDate(new DateTime()) ;
        $newInventaire->setDate(new DateTime()) ;

        if( $data->emplacement == 'MAGASIN'){
            $emplacement = $mag->find(1) ;
            $newInventaire->setMagasin($emplacement) ;
        }else{
            $emplacement = $dp->find(1) ;
            $newInventaire->setDepot($emplacement) ;
        }
        $entityManager = $this->entityManager ;
        $entityManager->persist($newInventaire) ;
        $entityManager->flush() ;

        for ($i=0; $i < count($data->produit); $i++) {
            $produit = $p->find($data->produit[$i]->id) ;
            
            for ($j=0; $j < count($data->produit[$i]->unite) ; $j++) {

                $unite = $u->find($data->produit[$i]->unite[$j]->id) ;

                /* MOUVEMENT STK */
                $mvtStk = new MouvementStock() ;
                $mvtStk->setProduits($produit) ;
                $mvtStk->setUniteP($unite) ;
                $mvtStk->setQte($data->produit[$i]->unite[$j]->valeur) ;
                $mvtStk->setInventaire($newInventaire) ;
                $mvtStk->setDescription('INVENTAIRE '.$data->produit[$i]->description) ;
                $mvtStk->setDate(new DateTime) ;
                $entityManager = $this->entityManager ;
                $entityManager->persist($mvtStk) ;
                $entityManager->flush() ;

                /* STOCK */
                if($data->emplacement == 'MAGASIN'){
                    $stock = $stk->findOneByProduitAndUniteAndMagasin($produit, $unite, $emplacement) ;
                }else{
                    $stock = $stk->findOneByProduitAndUniteAndDepot($produit, $unite, $emplacement) ;
                }
                
                if(isset($stock)){
                    $stock->setQte($data->produit[$i]->unite[$j]->valeur) ;
                    $entityManager = $this->entityManager;
                    $entityManager->persist($stock);
                    $entityManager->flush();
                }
            }
        }

        return $this->json(['Produits' => $stock], 200, [], ['groups' => 'produit:read']) ;
    }
}
