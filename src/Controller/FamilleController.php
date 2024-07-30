<?php

namespace App\Controller;

use App\Repository\FamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Famille;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/Famille', name: 'app_famille')]

class FamilleController extends AbstractController
{
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/getFamille', name: 'getFamille', methods: ['GET'])]
    public function getFamille( FamilleRepository $f )
    {
        return $this->json(['Famille' => $f->findBySuppr(0)], 200, [], ['groups' => 'produit:read']) ;
    }

    #[Route('/createFamille', name: 'createFamille', methods: ['POST'])]
    public function createFamille( Request $request, FamilleRepository $f )
    {
        $data = json_decode($request->getContent()) ;
        $newFamille = new Famille() ;
        $newFamille->setLibelle($data->Famille->libelle) ;
        $newFamille->setSuppr(0) ;
        $entityManager = $this->entityManager ;
        $entityManager->persist($newFamille) ;
        $entityManager->flush() ;

        return $this->json(['Famille' => $f->findBySuppr(0)], 200, [], ['groups' => 'produit:read']) ;
    }
}


