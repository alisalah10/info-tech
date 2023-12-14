<?php

namespace App\Controller;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/livre", name="app_livre")
     */
    public function index(): Response
    {
        $livre = $this->entityManager->getRepository(Livre::class)->findAll();
        return $this->render('livre/index.html.twig', [
            'livres' => $livre
        ]);
    }
}
