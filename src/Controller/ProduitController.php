<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit/{catalogue}", name="app_produit", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository, $catalogue): Response
    {
        $produits = $produitRepository->findBy(['catalogue' => $catalogue]);

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}
