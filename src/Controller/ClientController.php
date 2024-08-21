<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\PaiementCredit;
use App\Repository\ClientRepository;
use App\Repository\PaiementCreditRepository;
use App\Repository\VenteRepository;
use DateTime;
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

    #[Route('/updateCli', name: 'updateCli', methods:['POST'])]
    public function updateCli(  Request $request, ClientRepository $c )
    {
        $data = json_decode($request->getContent()) ;
        $cli = $c->find($data->client->id) ;
        $cli->setNom($data->client->nom) ;
        $cli->setContact($data->client->contact) ;
        $entityManager = $this->entityManager ;
        $entityManager->persist($cli) ;
        $entityManager->flush() ;
        
        return $this->json(['client'=> $c->findBySuppr(0)], 200, []) ;
    }


    //PAIEMENT
    #[Route('/getListFacture', name: 'getListFacture', methods:['POST'])]
    public function getListFacture(  Request $request, ClientRepository $c, VenteRepository $v )
    {
        $data = json_decode($request->getContent()) ;
        $cli = $c->find($data->client->id) ;
        return $this->json(['Facture'=> $v->findByClient($cli)], 200, [],  ['groups' => 'client:read']) ;
    }

    #[Route('/getDetailFacture', name: 'getDetailFacture', methods:['POST'])]
    public function getDetailFacture(  Request $request, VenteRepository $v, PaiementCreditRepository $p )
    {
        $data = json_decode($request->getContent()) ;
        $vente = $v->find($data->id) ;
        return $this->json(['DetailsFacture'=> $p->findByVente($vente)], 200, [],  ['groups' => 'client:read']) ;
    }

    #[Route('/paid', name: 'paid', methods:['POST'])]
    public function paid(  Request $request, ClientRepository $c, PaiementCreditRepository $p, VenteRepository $v )
    {
        $data = json_decode($request->getContent()) ;
        //COMPTE CLIENT
        $client = $c->find($data->client->id) ;
        $client->setCompte( $client->getCompte() - $data->client->compte) ;
        $entityManager = $this->entityManager;
        $entityManager->persist($client);
        $entityManager->flush();

        //PAIEMENT CREDIT
        $vente = $v->find($data->vente) ;
        $paiementCredit = $p->findLatestByVente($vente) ;
        $newpaiementCredit = new PaiementCredit ;
        $newpaiementCredit->setClient($client) ;
        $newpaiementCredit->setDate( new DateTime ) ;
        $newpaiementCredit->setResteAPaye( $paiementCredit->getResteAPaye() - $data->client->compte ) ;
        $newpaiementCredit->setMontantPaye( $data->client->compte ) ;
        $newpaiementCredit->setVente( $vente ) ;
        $entityManager = $this->entityManager;
        $entityManager->persist($newpaiementCredit);
        $entityManager->flush();

        if(($paiementCredit->getResteAPaye() - $data->client->compte) <= 0){
            $vente->setPaid(1) ;
            $entityManager = $this->entityManager;
            $entityManager->persist($vente);
            $entityManager->flush();
        }

        return $this->json(['client'=> $c->findBySuppr(0)], 200, []) ;
    }
}
