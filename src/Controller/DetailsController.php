<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DetailsController extends AbstractController
{
    /**
     * @Route("/livre/{id}", name="livre_details")
     */
    public function showDetails(LivreRepository $livreRepository, $id): Response
    {
        $livre_details = $livreRepository->find($id);

        if (!$livre_details) {
            throw $this->createNotFoundException('The product was not found');
        }

        return $this->render('livre/details.html.twig', [
            'livre' => $livre_details,
        ]);
    }
}
