<?php

namespace App\Controller;

use App\Entity\Product;

use App\Form\ProductType;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category', priority: -1)]

    // on mets une priorité negative pour ne pas avoir de soucie pour les autres routes /login par ou /logout

    public function category($slug, CategoryRepository $CategoryRepository): Response
    {
        $category = $CategoryRepository->findOneBy([
            'slug' => $slug
        ]);
        // si le slug n'est pas valid

        if (!$category) {
            throw $this->createNotFoundException("la categorie demandée n'existe pas");
        }
        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    // gestion de panier 

    #[Route('/{category_slug}/{slug}', name: 'product_show', priority: -1)]

    public function show(Product $product, $category_slug, CategoryRepository $CategoryRepository)
    {
        $category = $CategoryRepository->findOneBy([
            'slug' => $category_slug
        ]);
        if (!$category) {
            throw $this->createNotFoundException("le produit demandé n'existe pas");
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    //modifier un produit 

    #[Route('/admin/product/{id}/edit', name: 'product_edit')]

    public function edit($id, ProductRepository $productRepository, Request $request, SluggerInterface $slugger, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        // $form->setData($product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->flush();
            //redirection vers la page du produit modifié

            return $this->redirectToRoute("product_show", [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }
        $formView = $form->createView();
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }

    // formulaire de création d'un produit

    #[Route('/admin/product/create', name: 'product_create')]
    // je me fait livrer par gestion d'indépendance une FormFactoryIntefrace
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, UploaderService $uploaderService)
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $photo = $form->get('mainpicture')->getData();
            $photo2 = $form->get('mainpicture2')->getData();

            if ($photo || $photo2) {
                $directory = $this->getParameter('images_directory');
                $product->setMainpicture($uploaderService->uploadFile($photo, $directory));
                $product->setMainpicture2($uploaderService->uploadFile($photo2, $directory));
            }

            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute("product_show", [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/create.html.twig', ['formView' => $formView]);
    }
    // on peut rajouter une methode $builder->setMethod("GET"); et aussi une action $builder->setAction(/quelquechose);

    // fonction pour afficher les commandes des utilisateurs 
    #[Route('/purchases', name: 'purchases_index')]

    public function index()
    {
        //  vérifier si la personne est connectée sinon redirection vers la page d'acceuil
        /** @var User */
        $user = $this->getUser();

        if (!$user) {
            // si l'utilisateur n'est pas connecter il sera rediriger vers la page d'acceuil
            $url = $this->router->generate('homepage');
            return new RedirectResponse($url);
        }

        // passer l'utilisateur connecter à twig afin d'afficher les commandes 

        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}
