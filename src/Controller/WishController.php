<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Service\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Name;
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
        // $wishes = $wishRepository->findByIdSup(5);     fonctionne

        // $cat = new Category();
        // $cat->setId(1);
        // $wishes = $wishRepository->findByCat($cat);     fonctionne

        $wishes = $wishRepository->findByDateRecentAncien();     // fonctionne

        // $wishes = $wishRepository->findByCategorie();     fonctionne


        /*$wishes = $wishRepository->findAll();
        usort($wishes, function ($a, $b) {
            // Remplacez 'dateCreated' par la propriété sur laquelle vous souhaitez trier
            $dateA = $a->getDateCreated();
            $dateB = $b->getDateCreated();

            // Comparaison des dates
            if ($dateA == $dateB) {
                return 0;
            }
            return ($dateA < $dateB) ? -1 : 1;
        });*/                                       // fonctionne mais compliqué !

        return $this->render('wish/list.html.twig',[
            "wishes" => $wishes
        ]);
    }

    #[Route('/create', name: 'app_wish_create')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator): Response
    {
        $wish = new Wish();
        $wish->setDateCreated(new \DateTime());
        $wish->setIsPublished(true);

        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() &&
            $wishForm->isValid() == false &&
            $wishForm->getClickedButton() &&
            'cancel' === $wishForm->getClickedButton()->getName()) {
            return $this->redirectToRoute('app_wish_list', [
                'id' => $wish->getId()
            ]);
        }
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            if ($wishForm->getClickedButton() && 'validate' === $wishForm->getClickedButton()->getName()) {

                // Appel au service de censure
                $wish->setDescription($censurator->purify($wish->getDescription()));

                $entityManager->persist($wish);
                $entityManager->flush();

                $this->addFlash('success', 'Souhait ajouté avec succès !');
                return $this->redirectToRoute('app_wish_detail', [
                    'id' => $wish->getId()
                ]);
            }
            else {
                return $this->redirectToRoute('app_wish_list', [
                    'id' => $wish->getId()
                ]);
            }
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm,
            'wish' => $wish
        ]);
    }
    #[Route('/detail/{id}', name: 'app_wish_detail')]
    public function detail(Wish $wish, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($wish);
        // s'il n'existe pas en bdd, on déclenche une erreur 404
        if (!$wish){
            throw $this->createNotFoundException('This wish do not exists! Sorry!');
        }

        return $this->render('wish/detail.html.twig', [
            "wish" => $wish
        ]);
    }

    #[Route('/delete/{id}', name: 'app_wish_delete')]
    public function delete(Wish $wish, Request $request, EntityManagerInterface $entityManager): Response
    {
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            if ($wishForm->getClickedButton() && 'validate' === $wishForm->getClickedButton()->getName()) {
                $entityManager->remove($wish);
                $entityManager->flush();

                $this->addFlash('success', 'Suppression réussie !');
            }

            return $this->redirectToRoute('app_wish_list', [
                'id' => $wish->getId()
            ]);
        }

        return $this->render('wish/delete.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'app_wish_update')]
    public function update(Wish $wish, Request $request, EntityManagerInterface $entityManager): Response
    {
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            if ($wishForm->getClickedButton() && 'validate' === $wishForm->getClickedButton()->getName()) {
                $wish->setDateCreated(new \DateTime());
                $entityManager->persist($wish);
                $entityManager->flush();

                $this->addFlash('success', 'Modification réussie !');
            }
            return $this->redirectToRoute('app_wish_list', [
                'id' => $wish->getId()
            ]);
        }

        return $this->render('wish/update.html.twig', [
            'wishForm' => $wishForm
        ]);
    }
}
