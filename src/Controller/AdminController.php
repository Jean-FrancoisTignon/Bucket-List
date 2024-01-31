<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/tableauDeBord', name: 'tableauDeBord')]
    public function index(WishRepository $wishRepository,
                          CategoryRepository $categoryRepository,
                          EntityManagerInterface $manager): Response
    {
        $categories = $categoryRepository->findAll();
        $nbCategories = count($categories);

        $wishes = $wishRepository->findAll();
        $nbWishes = count($wishes);

        $categoriesWishes = [];


        for ($i = 0 ; $i < $nbCategories; $i++)
        {

            $categoryRepository = $manager->getRepository(Category::class);
            $category = $categoryRepository->findBy(['id' => $i]);
            $categoriesWishes[$i] = 0;
            for ($j = 0; $j < $nbWishes; $j++)
            {
                if (isset($wishes[$j]) && $wishes[$j]->getCategory($category)->getId() == $i) {
                    $categoriesWishes[$i]++;
                }
            }
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'wishes' => $wishes,
            'nbWishes' => $nbWishes,
            'categories' => $categories,
            'nbCategories' => $nbCategories,
            'categoriesWishes' => $categoriesWishes
        ]);
    }
}
