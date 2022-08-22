<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class CartService
{
    protected $requestStack;
    protected $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }
    public function add(int $id)
    {
        // retrouver le panier dans la session sous forme de tableau et s'il n'existe pas prendre un tableau vide

        $cart = $this->requestStack->getSession()->get('cart', []);

        // verifier si le produit existe deja dans le panier 

        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {

            // sinon ajouter le produit au panier avec la quantité 1

            $cart[$id] = 1;
        }

        // ensuite enresitrer le tableau mis à jour dans la session 
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$id]);

        $this->requestStack->getSession()->set('cart', $cart);
    }


    public function decrement(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);


        // si le produit existe

        if (!array_key_exists($id, $cart)) {
            return;
        }
        // si le produit=1 il faut le supprimer

        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }
        // si le produit >1 il faut le décrementer 
        $cart[$id]--;
        $this->requestStack->getSession()->set('cart', $cart);
    }
    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }
            $total += $product->getPrice() * $qty;
        }
        return $total;
    }
    public function getDetailedCartItems(): array
    {

        $detailedCart = [];

        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);
        }

        // pour vide le panier $this->requestStack->getSession()->remove('cart');

        return $detailedCart;
    }
}
