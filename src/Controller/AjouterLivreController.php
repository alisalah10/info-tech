<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Form\ModifierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AjouterLivreController extends AbstractController
{
    /**
     * @Route("/app_book_add", name="app_book_add", methods={"GET", "POST"})
     */
    public function addLivre(Request $request, SluggerInterface $slugger): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Check if a book with the same name already exists
            $existingLivre = $entityManager->getRepository(Livre::class)->findOneBy(['nom' => $livre->getNom()]);
            if ($existingLivre) {
                $this->addFlash('error', 'Le livre avec ce nom existe déjà.');
                return $this->redirectToRoute('app_book_add');
            }

            // Handle photo upload
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                try {
                    $photo->move(
                        $this->getParameter('photo'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if file upload fails
                    $this->addFlash('error', 'Une erreur s\'est produite lors de l\'upload de la photo.');
                    return $this->redirectToRoute('app_book_add');
                }

                $livre->setPhoto($newFilename);
            }

            // Set the utilisateur (user) for the livre
            $user = $this->getUser();
            $livre->setUtilisateur($user);

            $entityManager->persist($livre);
            $entityManager->flush();

            // Display success flash message
            $this->addFlash('success', 'Le livre a été ajouté avec succès.');

            //return $this->redirectToRoute('livres_ajoutes');
        }

        return $this->render('ajouterLivre/add_livre.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/livres-ajoutes", name="livres_ajoutes")
     */
    public function livresAjoutes()
    {
        $user = $this->getUser();
        $livres = $user->getLivres();

        return $this->render('ajouterLivre/index.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/livres-ajoutes/supprimer/{id}", name="livres_ajoutes_supprimer", methods={"GET", "POST"})
     */
    public function supprimerLivre(Livre $livre): RedirectResponse
    {
        $user = $this->getUser();

        // Check if the book belongs to the current user
        if ($livre->getUtilisateur() === $user) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livre);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à supprimer ce livre.');
        }

        return $this->redirectToRoute('livres_ajoutes');
    }


    /**
     * @Route("/livres-ajoutes/modifier/{id}", name="livres_ajoutes_modifier", methods={"GET", "POST"})
     */
    public function modifierLivre(Request $request, Livre $livre): Response
    {
        $user = $this->getUser();

        // Check if the book belongs to the current user
        if ($livre->getUtilisateur() !== $user) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à modifier ce livre.');
            return $this->redirectToRoute('livres_ajoutes');
        }

        $form = $this->createForm(ModifierType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le livre a été modifié avec succès.');

            return $this->redirectToRoute('livres_ajoutes');
        }

        return $this->render('ajouterLivre/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
