<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailsController extends AbstractController
{
    /**
     * @Route("/product/{id}", name="product_details")
     */
    public function showDetails(ProduitRepository $produitRepository, $id): Response
    {
        $produit_details = $produitRepository->find($id);

        if (!$produit_details) {
            throw $this->createNotFoundException('The product was not found');
        }

        return $this->render('produit/details.html.twig', [
            'produit' => $produit_details,
        ]);
    }

    /**
     * @Route("/product/{id}/buy", name="buy_product", methods={"POST"})
     */
    public function updateProductQuantity(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Produit::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $newQuantity = $product->getQuantite() - 1;
        if ($newQuantity < 0) {
            return $this->json(['success' => false, 'message' => 'Product out of stock']);
        }

        $product->setQuantite($newQuantity);

        $entityManager->flush();

        return $this->json(['success' => true, 'message' => 'Product quantity updated']);
    }
}
