<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\PaiementFrs;
use App\Repository\AchatRepository;
use App\Repository\FournisseurRepository;
use App\Repository\MouvementStockRepository;
use App\Repository\PaiementFrsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/Fournisseur', name: 'app_fournisseur')]
class FournisseurController extends AbstractController
{
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/getListeFournisser', name: 'getFournisseur', methods:['GET'])]
    public function getFournisseur(FournisseurRepository $f)
    {
        return $this->json(['Fournisseur' => $f->findBySuppr(0)], 200, []) ;
    }

    #[Route('/createFrs', name: 'createFrs', methods:['POST'])]
    public function createFrs( Request $request ,FournisseurRepository $f)
    {
        $data = json_decode($request->getContent()) ;
        $newFournisseur = new Fournisseur() ;

        $newFournisseur->setNom($data->fournisseur->nom) ;
        $newFournisseur->setAdresse($data->fournisseur->adresse) ;
        $newFournisseur->setContact($data->fournisseur->contact) ;
        $newFournisseur->setcompte($data->fournisseur->compte) ;
        $newFournisseur->setSuppr(0) ;

        $entityManager = $this->entityManager;
        $entityManager->persist($newFournisseur);
        $entityManager->flush();

        return $this->json(['Fournisseur' => $f->findBySuppr(0)], 200, []) ;
    }

    #[Route('/updateFrs', name: 'updateFrs', methods:['POST'])]
    public function updateFrs( Request $request ,FournisseurRepository $f)
    {
        $data = json_decode($request->getContent()) ;
        $fournisseur = $f->find($data->fournisseur->id) ;

        $fournisseur->setNom($data->fournisseur->nom) ;
        $fournisseur->setAdresse($data->fournisseur->adresse) ;
        $fournisseur->setContact($data->fournisseur->contact) ;
        $fournisseur->setcompte($data->fournisseur->compte) ;

        $entityManager = $this->entityManager;
        $entityManager->persist($fournisseur);
        $entityManager->flush();

        return $this->json(['Fournisseur' => $f->findBySuppr(0)], 200, []) ;
    }

    #[Route('/delete', name: 'delete', methods:['POST'])]
    public function delete( Request $request ,FournisseurRepository $f)
    {
        $data = json_decode($request->getContent()) ;
        $fournisseur = $f->find($data->fournisseur->id) ;

        $fournisseur->setSuppr(1) ;

        $entityManager = $this->entityManager;
        $entityManager->persist($fournisseur);
        $entityManager->flush();

        return $this->json(['Fournisseur' => $f->findBySuppr(0)], 200, []) ;
    }

    #[Route('/getListFacture', name: 'getListFacture', methods:['POST'])]
    public function getListFacture( Request $request ,FournisseurRepository $f, AchatRepository $achat)
    {
        $data = json_decode($request->getContent()) ;
        $fournisseur = $f->find($data->data->id) ;
        return $this->json(['Facture' => $achat->findByFournisseur($fournisseur)], 200, []) ;
    }

    #[Route('/getDetailListFacture', name: 'getDetailListFacture', methods:['POST'])]
    public function getDetailListFacture( Request $request ,AchatRepository $a, PaiementFrsRepository $p)
    {
        $data = json_decode($request->getContent()) ;
        $achat = $a->find($data->data) ;
        return $this->json(['FactureDetails' => $p->findByAchat($achat)], 200, []) ;
    }


    #[Route('/paid', name: 'paid', methods:['POST'])]
    public function paid( Request $request ,FournisseurRepository $f, AchatRepository $a, PaiementFrsRepository $p)
    {
        $data = json_decode($request->getContent()) ;
        if($data->achat != 0){
            $fournisseur = $f->find($data->fournisseur->id) ;
            $fournisseur->setcompte( $fournisseur->getcompte() - $data->fournisseur->compte ) ;
            $entityManager = $this->entityManager;
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            $achat = $a->find($data->achat) ;
            $reste = $p->findLatestByAchat($achat);
            $resteApayer = $reste->getReste() ;

            $paiement =  new PaiementFrs() ;
            $paiement->setAchat($achat) ;
            $paiement->setDate(new DateTime) ;
            $paiement->setMontantP( $data->fournisseur->compte ) ;
            $paiement->setReste( $resteApayer - $data->fournisseur->compte ) ;
            $entityManager = $this->entityManager;
            $entityManager->persist($paiement);
            $entityManager->flush();

            if($resteApayer - $data->fournisseur->compte<=0){
                $achat->setPaid(1) ;
                $entityManager = $this->entityManager;
                $entityManager->persist($achat);
                $entityManager->flush();
            }
        }
        return $this->json(['Fournisseur' => $f->findBySuppr(0)], 200, []) ;
    }

    /* HISTORIQUE ACHAT */
    #[Route('/getHistoAchat', name: 'getHistoAchat', methods:['POST'])]
    public function getHistoAchat( Request $request, MouvementStockRepository $m, FournisseurRepository $f)
    {
        $data = json_decode($request->getContent()) ;
        $frs = $f->find($data->data) ;
        return $this->json(['historique' => $m->findByFournisseur($frs)], 200, [],['groups' => 'produit:read']) ;
    }
}
