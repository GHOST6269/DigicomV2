<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
#[Route('api/utilisateur', name: 'firstUser')]
class UtilisateurController extends AbstractController
{
    private $entityManager ;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    
    #[Route('/getUserList', name: 'getUserList', methods: ['GET'])]
    public function getUserList( UtilisateurRepository $u ){
        return $this->json(['user' => $u->findBySuppr(0)], 200, [], ['groups' => 'user:read']) ;
    }


    #[Route('/create', name: 'createUser', methods: ['POST'])]
    public function create(Request $request, UserPasswordHasherInterface  $passwordHasher, UtilisateurRepository $u)
    {
        $data = json_decode($request->getContent());
        $newUserLogin = new Utilisateur();
        $newUserLogin->setUsername($data->user->username);
        $newUserLogin->setPrenom($data->user->prenom);
        $newUserLogin->setPrivilege($data->user->privilege);
        $newUserLogin->setSuppr(0);
        $newUserLogin->setNom($data->user->nom);
        $newUserLogin->setContact($data->user->contact);
        
        $password = $data->user->password ;
        $hashedPassword = $passwordHasher->hashPassword(
            $newUserLogin,
            $password
        ) ;

        $newUserLogin->setPassword($hashedPassword);

        $entityManager = $this->entityManager;
        $entityManager->persist($newUserLogin);
        $entityManager->flush();

        return $this->json(['user' => $u->findBySuppr(0)], 200, [], ['groups' => 'user:read']) ;
    }

    #[Route('/update', name: 'update', methods: ['POST'])]
    public function update ( Request $request, UtilisateurRepository $u, UserPasswordHasherInterface  $passwordHasher){
        $data = json_decode($request->getContent()) ;
        $user = $u->find($data->user->id) ;

        $user->setUsername($data->user->username);
        $user->setNom($data->user->nom);
        $user->setPrenom($data->user->prenom);
        $user->setPrivilege($data->user->privilege);
        $user->setContact($data->user->contact);

        if($data->user->password!= '' && isset($data->user->password)){
            $password = $data->user->password ;
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            ) ;
            $user->setPassword($hashedPassword);
        }
        

        $entityManager = $this->entityManager;
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json(['user' => $u->findBySuppr(0)], 200, [], ['groups' => 'user:read']) ;
    }

    #[Route('/delete', name: 'delete', methods: ['POST'])]
    public function delete( Request $request, UtilisateurRepository $u ){
        $data = json_decode($request->getContent()) ;
        $user = $u->find($data->user->id) ;
        $user->setUsername($data->user->username.'(Supprimé)');
        $user->setNom($data->user->nom.'(Supprimé)') ;
        $user->setPrenom($data->user->prenom.'(Supprimé)');
        $user->setSuppr(1) ;
        $entityManager = $this->entityManager ;
        $entityManager->persist($user) ;
        $entityManager->flush() ;
        return $this->json(['user' => $u->findBySuppr(0)], 200, [], ['groups' => 'user:read']) ;
    }
}
