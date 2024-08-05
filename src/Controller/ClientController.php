<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/client', name: 'app_client')]

class ClientController extends AbstractController
{
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/getClient', name: 'getClient', methods:['GET'])]
    public function getClient( ClientRepository $c )
    {
        return $this->json(['client'=> $c->findBySuppr(0)], 200, []) ;
    }
    
    #[Route('/CreateCli', name: 'CreateCli', methods:['POST'])]
    public function CreateCli(  Request $request, ClientRepository $c )
    {
        $data = json_decode($request->getContent()) ;
        $newCli = new Client() ;
        $newCli->setNom($data->client->nom) ;
        $newCli->setContact($data->client->contact) ;
        $newCli->setCompte(0) ;
        $newCli->setSuppr(0) ;
        $entityManager = $this->entityManager ;
        $entityManager->persist($newCli) ;
        $entityManager->flush() ;
        
        return $this->json(['client'=> $c->findBySuppr(0)], 200, []) ;
    }
}
