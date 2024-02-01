<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/user', name: 'app_user_index')]
    public function index(WishRepository $wishRepository): Response
    {
        /* Si on doit travailler sur le user

        if ($this->getUser())
        {
            $user = $this->getUser();
        }
        */
    // en guise d'exemple car:
    // la table USER a comme arguments un email, un pseud
    // alors que la table WISH prend le nom de l'auteur de l'article.
    // Bref essai avec author = 'Moi'
        $wishes = $wishRepository->findBy(['author' => 'Moi'], ["dateCreated" => "DESC"],20);
        $nbWish = count($wishes);
        return $this->render('user/index.html.twig', [
            'nbWish' => $nbWish,
            'wishes' => $wishes
        ])
        ;
    }
}
