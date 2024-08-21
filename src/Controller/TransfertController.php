<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/transfert', name: 'app_transfert')]

class TransfertController extends AbstractController
{
    #[Route('/validateTransfert', name: 'app_transfert')]
    public function index()
    {
        return $this->render('transfert/index.html.twig', [
            'controller_name' => 'TransfertController',
        ]);
    }
}
