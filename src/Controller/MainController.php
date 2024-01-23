<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_home')]
    public function home()
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/test', name: 'app_main_test')]
    public function test()
    {
        return $this->render('main/test.html.twig');
    }

    #[Route('/about-us', name: 'app_main_about_us')]
    public function aboutUs()
    {
        // Chemin vers le fichier JSON
        $cheminFichierJson = '../data/team.json';

        // Récupération du contenu du fichier JSON
        $contenuJson = file_get_contents($cheminFichierJson);

        // Décodage du JSON en une variable PHP
        $donnees = json_decode($contenuJson, true);

        // Vérification des erreurs de décodage
        if ($donnees === null && json_last_error() !== JSON_ERROR_NONE) {
            // Gestion des erreurs de décodage
            die('Erreur de décodage JSON : ' . json_last_error_msg());
        }

        // Utilisez $donnees comme une variable PHP contenant les données du fichier JSON
        return $this->render('main/about_us.html.twig', ["donnees" => $donnees]);
    }
}