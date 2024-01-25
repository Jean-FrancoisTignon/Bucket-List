<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wishes')]
class WishController extends AbstractController
{
    #[Route('/', name: 'app_wish_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(["isPublished" => true],["dateCreated" => "DESC"], 20);

        return $this->render('wish/list.html.twig',[
            "wishes" => $wishes
        ]);
    }

    #[Route('/create', name: 'app_wish_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wish = new Wish();
        $wish->setDateCreated(new \DateTime());
        $wish->setIsPublished(true);

        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Souhait ajouté avec succès !');
            return $this->redirectToRoute('app_wish_detail', [
                'id' => $wish->getId()
            ]);
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm
        ]);
    }
    #[Route('/detail/{id}', name: 'app_wish_detail')]
    public function detail($id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        // s'il n'existe pas en bdd, on déclenche une erreur 404
        if (!$wish){
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }

        return $this->render('wish/detail.html.twig', [
            "wish" => $wish
        ]);
    }

    #[Route('/delete/{id}', name: 'app_wish_delete')]
    public function delete(Wish $wish, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($wish);
        $entityManager->flush();

        return $this->redirectToRoute('app_wish_list');
    }

    #[Route('/update/{id}', name: 'app_wish_update')]
    public function update(Wish $wish, EntityManagerInterface $entityManager): Response
    {


        $entityManager->flush();

        return $this->redirectToRoute('app_wish_list');
    }
}
