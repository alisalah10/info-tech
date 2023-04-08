<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;


class DetailsUserController extends AbstractController
{
    /**
     * @Route("/informations", name="user_details_app")
     */
    public function index(Security $security)
    {
        $user = $security->getUser();

        return $this->render('user/details.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/user/update", name="update_user_app")
     */
    public function updateUser(Request $request)
    {
        $user = $this->getUser(); // get the current user

        $form = $this->createForm(UserType::class, $user); // create a form with UserType as the form type, and pass the current user as the data for the form

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // handle form submission, save changes to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_details_app'); // redirect back to the user details page
        }

        return $this->render('user/update_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
