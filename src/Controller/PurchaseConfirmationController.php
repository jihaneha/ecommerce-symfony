<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Form\CartConfirmationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException as ExceptionAccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController extends AbstractController
{

    // je me fais livrer les dépendances directement au constructeur
    protected $formFactory;
    protected $router;
    protected $security;
    protected $cartService;
    protected $em;


    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
    }


    #[Route('/purchase/confirm', name: 'purchase_confirm')]

    public function confirm(Request $request)
    {
        //lire les données du formulaires 

        //Request

        $form = $this->formFactory->create(CartConfirmationType::class);

        // j'annalyse la request je peux pas la demandée au constructeur car elle est unique au constructeur en demande des services qui change pas 

        $form->handleRequest($request);

        //si le formulaire n'a pas été soumis 
        if (!$form->isSubmitted()) {

            //message flash puis redirection
            //le flashbag est relier a la session = la requete du coup je peux pas le demander au constructeur
            $this->addFlash('warning', 'vous devez remplir le formulaire de confirmation');
            return new RedirectResponse($this->router->generate('cart_show'));
        }
        // si je ne suis pas connecter 
        $user = $this->security->getUser();

        if (!$user) {
            throw new ExceptionAccessDeniedException("vous devez etre connecter pour confirmer une commande");
        }

        //si le panier est vide
        $cartItems = $this->cartService->getDetailedCartItems();
        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'vous ne pouvez pas confirmer la commande avec un panier vide');
            return new RedirectResponse($this->router->generate('cart_show'));
        }

        //nous creons une purchase
        /** @var Purchase */
        $purchase = $form->getData();

        //nous allons la lier avec l'utilisateur connecté
        $purchase->setUser($user)
            ->setPurchasedAt(new DateTime());
        $this->em->persist($purchase);
        //nous allons la lier avec les produits dans le panier

        $total = 0;

        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getPrice())
                ->setQuatity($cartItem->qty)
                ->setTotal($cartItem->getTotal)
                ->setProductPrice($cartItem->product->getPrice);

            $total += $cartItem->getTotal;
            $this->em->persist($purchaseItem);
        }

        $purchase->setTotal($total);
        //nous allons enregistrer la commande

        $this->em->flush();
        $this->addFlash('success', 'la commande à bien été enregistrée');
        return new RedirectResponse($this->router->generate('purchase_index'));
    }
}
