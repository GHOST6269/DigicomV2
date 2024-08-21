<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /* #[Route('api/login', name: 'firstUser')]
    public function firstUser(Request $request, UserPasswordHasherInterface  $passwordHasher)
    {
        $data = json_decode($request->getContent());
        $newUserLogin = new Utilisateur();
        $newUserLogin->setUsername('Mamy');
        $newUserLogin->setPrenom('Rakotomamonjy');
        $newUserLogin->setPrivilege('Admin');
        $newUserLogin->setSuppr(0);
        $newUserLogin->setNom('Mamy');
        $newUserLogin->setContact('contact');
        
        $password = '123456' ;
        // Encodez le mot de passe avant de l'enregistrer
        $hashedPassword = $passwordHasher->hashPassword(
            $newUserLogin,
            $password
        );

        $newUserLogin->setPassword($hashedPassword);

        $entityManager = $this->entityManager;
        $entityManager->persist($newUserLogin);
        $entityManager->flush();

        return  $this->json(['create']);
    } */
}
