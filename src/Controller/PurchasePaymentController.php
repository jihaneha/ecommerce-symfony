<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentController extends AbstractController
{
    #[Route('/purchase/pay/{id}', name: 'purchase_payment_form')]
    #[IsGranted('ROLE_USER')]
    public function showCardForm($id, PurchaseRepository $purchaseRepository)
    {

        // je cherche d'abord ma commande 

        $purchase = $purchaseRepository->find($id);

        // s'il n'y a pas de commande j'utilisateur sera rediriger vers la page du panier 

        if (!$purchase) {
            return $this->redirectToRoute('cart_show');
        }
        //on insere la clé secrete de stripe 

        \Stripe\Stripe::setApiKey('sk_test_51Lc55GEE4qJMJDVRDbz7kIvqcCNx59u6TDzVFWCXvQw2a9PeAYchxpkTtYnW8WLRFRqJsfsWP5wqtC6KPE4mKvWB00xUHwLEDu');

        // ensuite on va créer un paiement 
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $purchase->getTotal(),
            'currency' => 'eur'
        ]);



        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase
        ]);
    }
}
