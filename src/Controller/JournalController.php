<?php

namespace App\Controller;

use App\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/journal', name: 'app_journal')]
class JournalController extends AbstractController
{
    #[Route('/vente', name: 'vente')]
    public function vente( VenteRepository $v )
    {
        return $this->json([ $v->findAll() ]) ;
    }

    #[Route('/achat', name: 'achat')]
    public function achat()
    {
        return $this->json([]) ;
    }
}
