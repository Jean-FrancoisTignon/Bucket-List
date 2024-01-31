<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
   // #[Route('/user/{id}', name: 'app_user_index')]
    #[Route('/user', name: 'app_user_index')]
    //public function index(User $user, UserRepository $userRepository): Response
    public function index(UserRepository $userRepository): Response

    {
        /*$user = $userRepository->find();
        if (!$user){
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas, désolé !');
        }*/
        /*return $this->render('user/index.html.twig', [
            'user' => $user
        ])*/
        return $this->render('user/index.html.twig', [
        ]);
    }
}
