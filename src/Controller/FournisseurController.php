<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
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

    

}
