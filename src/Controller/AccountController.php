<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('account/changepassword', name: 'change_password')]
    public function changepassword(HttpFoundationRequest $request,UserPasswordHasherInterface $encoder,EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

 // si le formulaire à été envoyé et s'il est valide

        if ($form->isSubmitted() && $form->isValid()) {
// je verifie d'abord si le mdp saisie est différent de celui sur la bdd
        //je recupère l'ancien mdp
       $old_password=$form->get('old_password')->getData();
       if($encoder->isPasswordValid($user,$old_password)){
        //je récupere de nouveau mdp saisie
        $new_password=$form->get('new_password')->getData();
        //j'encode le nouveau mdp
        $password=$encoder->hashPassword($user,$new_password);

        $user->setPassword($password);
       
        //mettre à jour le mdp
        $em->flush();
        $this->addFlash('success', 'Votre mot de passe à été modifié avec succée ');
        }
        }    
        $user = $form->getData();
        return $this->render('account/changepassword.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
