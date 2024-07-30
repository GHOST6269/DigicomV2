<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/client', name: 'app_client')]

class ClientController extends AbstractController
{
    #[Route('/getClient', name: 'getClient')]
    public function getClient( ClientRepository $c )
    {
        return $this->json(['client'=> $c->findBySuppr(0)], 200, [], ['groups' => 'client:read']) ;
    }
}
