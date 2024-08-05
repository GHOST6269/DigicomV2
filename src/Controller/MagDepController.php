<?php

namespace App\Controller;

use App\Repository\DepotRepository;
use App\Repository\MagasinRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/MagDep', name: 'app_mag_dep')]

class MagDepController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/getMagasinList', name: 'getMagasinList', methods: ['GET'])]
    public function getMagasinList( MagasinRepository $mag )
    {
        return $this->json(['MagasinList' => $mag->findBySuppr(0)], 200, [], ['groups'=>'produit:read']) ;
    }

    #[Route('/getDepotList', name: 'getDepotList', methods: ['GET'])]
    public function getDepotList( DepotRepository $depot )
    {
        return $this->json([ 'DepotList' => $depot->findBySuppr(0)], 200, [], ['groups'=>'produit:read']) ;
    }

    #[Route('/getSociete', name: 'getSociete', methods: ['GET'])]
    public function getSociete( SocieteRepository $soc )
    {
        return $this->json([ 'Societe' => $soc->find(1)]) ;
    }
}
