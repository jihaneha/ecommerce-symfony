<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentSuccessController extends AbstractController
{
    #[Route('/purchase/terminate/{id}', name: 'purchase_payment_success')]
    #[IsGranted('ROLE_USER')]

    public function success($id, PurchaseRepository $purchaseRepository, EntityManager $em, CartService $cartService)
    {
        // je recupere la commannde 
        $purchase = $purchaseRepository->find($id);

        if (!$purchase || ($purchase && $purchase->getUser !== $this->getUser()) || ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)) {
            $this->addFlash('warning', "la commande n'existe pas");
            return $this->redirectToRoute("purchase_index");
        }
        // je la fais passer au statut (PAID)
        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();
        //je vide le panier 
        $cartService->empty();
        // je redirige avec un flash vers la liste des commandes
        $this->addFlash('success', "La commande a éte payée et confirmée !");
        return $this->redirectToRoute("purchase_index");
    }
}
