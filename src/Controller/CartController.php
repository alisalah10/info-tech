<?php

namespace App\Controller;


use App\Entity\Commande;
use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_show")
     */
    public function showCart()
    {
        $cart = $this->get('session')->get('cart', []);

        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $book = $this->getDoctrine()->getRepository(Livre::class)->find($id);

            if (!$book) {
                continue;
            }

            $itemTotal = $book->getPrix() * $quantity;
            $user = $this->getUser();
            $cartItem = new Commande($user);
            $cartItem->setBookId($book->getId())
                ->setBookName($book->getNom())
                ->setQuantity($quantity)
                ->setPrice($book->getPrix());

            $cartItems[] = $cartItem;
            $total += $itemTotal;
        }

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function addToCart($id, Request $request)
    {
        $this->addBookToCart($id);

        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function removeFromCart($id)
    {
        $this->removeBookFromCart($id);

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/cart/clear", name="cart_clear")
     */
    public function clearCart()
    {
        $this->get('session')->remove('cart');

        return $this->redirectToRoute('cart_show');
    }

    private function addBookToCart($id)
    {
        $cart = $this->get('session')->get('cart', []);
        if (!isset($cart[$id])) {
            $cart[$id] = 0;
        }
        $cart[$id]++;
        $this->get('session')->set('cart', $cart);
    }

    private function removeBookFromCart($id)
    {
        $cart = $this->get('session')->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }
        $this->get('session')->set('cart', $cart);
    }

    /**
     * @Route("/cart/increase/{id}", name="cart_increase")
     */
    public function increaseQuantity($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]++;
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/cart/decrease/{id}", name="cart_decrease")
     */
    public function decreaseQuantity($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/cart/place-order", name="cart_place_order", methods={"POST"})
     */
    public function placeOrder(Request $request, Security $security)
    {
        $cart = $this->get('session')->get('cart', []);

        if (empty($cart)) {
            return $this->redirectToRoute('cart_show');
        }

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($cart as $id => $quantity) {
            $book = $this->getDoctrine()->getRepository(Livre::class)->find($id);

            if (!$book) {
                continue;
            }

            // Update the quantity of the livre
            $book->setQuantite($book->getQuantite() - $quantity);

            // Create and persist the commande
            $user = $this->getUser();
            $commande = new Commande($user);
            $commande->setBookId($book->getId())
                ->setBookName($book->getNom())
                ->setQuantity($quantity)
                ->setPrice($book->getPrix())
                ->setUser($user);

            $entityManager->persist($commande);
        }

        $entityManager->flush();

        // Clear the cart
        $this->get('session')->remove('cart');

        return $this->redirectToRoute('cart_show');
    }
}
