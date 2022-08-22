<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Doctrine\ORM\EntityManager;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/{category_slug}/{slug}', name: 'product_show')]

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
    public function create(FormFactoryInterface $factory, Request $request, SluggerInterface $slugger, EntityManagerInterface $em, UploaderService $uploaderService)
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
}
