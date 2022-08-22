<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'])]

    public function add($id, ProductRepository $productRepository, CartService $cartService, Request $request)
    {
        // verifier si le produit existe

        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("le produit $id n'existe pas!");
        }

        $cartService->add($id);

        //rajouter un message de notification pour l'utilisateur 

        $this->addFlash('success', 'Le produit a bien été ajouté au panier');

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute("cart_show");
        }

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }
    // affichage panier

    #[Route('/cart', name: 'cart_show')]
    public function show(CartService $cartService)
    {
        $detailedCart = $cartService->getDetailedCartItems();

        $total = $cartService->getTotal();
        // pour vider le panier $session->remove('cart');


        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }

    // supprimer un produit du panier

    #[Route('/cart/delete/{id}', name: 'cart_delete', requirements: ['id' => '\d+'])]

    public function delete($id, ProductRepository $productRepository, CartService $cartService)
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas être supprimer !");
        }
        $cartService->remove($id);

        $this->addFlash('success', 'Le produit a bien été supprimer du panier');

        return $this->redirectToRoute("cart_show");
    }

    //décrémenter un produit dans le panier
    #[Route('/cart/decrement/{id}', name: 'cart_decrement', requirements: ['id' => '\d+'])]

    public function decrement($id, ProductRepository $productRepository, CartService $cartService)
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas être décrémenter !");
        }

        $cartService->decrement($id);

        $this->addFlash('success', 'Le produit a bien été décrémenté');

        return $this->redirectToRoute("cart_show");
    }
}
