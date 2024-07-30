<?php

namespace App\Controller;

use App\Repository\CommandeFournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/approvisionnement', name: 'app_approvisionnement')]

class ApprovisionnementController extends AbstractController
{
    #[Route('/AlllistAppro', name: 'AlllistAppro', methods:['GET'])]
    public function AlllistAppro( CommandeFournisseurRepository $c )
    {
        return $this->json(['Commande' => $c->findAll()], 200, [], ['groups' => 'produit:read']) ;
    }
}
