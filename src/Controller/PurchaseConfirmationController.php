<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class PurchaseConfirmationController extends AbstractController
{

    // je me fais livrer les dépendances directement au constructeur
    protected $formFactory;
    protected $router;
    protected $security;
    protected $cartService;
    protected $em;


    public function __construct(CartService $cartService, EntityManagerInterface $em)
    {
        $this->cartService = $cartService;
        $this->em = $em;
    }


    #[Route('/purchase/confirm', name: 'purchase_confirm')]
    #[IsGranted('ROLE_USER', message: 'vous devez être connecter pour confirmer une commande')]
    public function confirm(Request $request)
    {
        //lire les données du formulaires 

        //Request

        $form = $this->createForm(CartConfirmationType::class);

        // j'annalyse la request je peux pas la demandée au constructeur car elle est unique au constructeur en demande des services qui change pas 

        $form->handleRequest($request);

        //si le formulaire n'a pas été soumis 
        if (!$form->isSubmitted()) {

            //message flash puis redirection
            //le flashbag est relier a la session = la requete du coup je peux pas le demander au constructeur
            $this->addFlash('warning', 'vous devez remplir le formulaire de confirmation');
            return $this->redirectToRoute('cart_show');
        }
        // si je ne suis pas connecter 
        $user = $this->getUser();

        // if (!$user) {
        //     $url = $this->router->generate('security_login');
        //     return new RedirectResponse($url);
        // }

        //si le panier est vide
        $cartItems = $this->cartService->getDetailedCartItems();
        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'vous ne pouvez pas confirmer la commande avec un panier vide');
            return $this->redirectToRoute('cart_show');
        }

        //nous creons une purchase
        /** @var Purchase */
        $purchase = $form->getData();

        //nous allons la lier avec l'utilisateur connecté
        $purchase->setUser($user)
            ->setPurchasedAt(new DateTimeImmutable())
            ->setTotal($this->cartService->getTotal());
        $this->em->persist($purchase);
        //nous allons la lier avec les produits dans le panier

        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal())
                ->setProductPrice($cartItem->product->getPrice());

            $this->em->persist($purchaseItem);
        }

        //nous allons enregistrer la commande

        $this->em->flush();

        $this->cartService->empty();

        $this->addFlash('success', 'la commande à bien été enregistrée');
        return $this->redirectToRoute('purchases_index');
    }
}
