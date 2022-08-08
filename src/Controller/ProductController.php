<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category')]
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
    // formulaire de création d'un produit

    #[Route('/admin/product/create', name: 'product_create')]

    public function create(FormFactoryInterface $factory, CategoryRepository $categoryRepository)
    {
        $builder = $factory->createBuilder();
        // les champs du formulaire 
        $builder->add("name", TextType::class, [
            "label" => "nom du produit",
            "attr" => [
                'class' => "form-control",
                "placeholder" => "Veuillez saisir le nom du produit"
            ]
        ])
            ->add("shortDescription", TextareaType::class, [
                "label" => "description courte",
                "attr" => [
                    'class' => "form-control",
                    "placeholder" => "Veuillez saisir une petite description"
                ]
            ])
            ->add("price", MoneyType::class, [
                "label" => "prix du produit",
                "attr" => [
                    'class' => "form-control",
                    "placeholder" => "Veuillez saisir le prix du produit en €"
                ]
            ]);
        $options = [];
        foreach ($categoryRepository->findAll() as $category) {
            $options[$category->getName()] = $category->getId();
        }

        $builder->add("category", ChoiceType::class, [
            "label" => "Category",
            "attr" => [
                'class' => "form-control"
            ],
            "placeholder" => "--choisir une categorie --",
            "choices" => $options
        ]);
        $form = $builder->getForm();

        // la classe form est immense elle permet de representer la config du formulaire et traiter les requete faire la validation etc twig ne va pas s'en sortir avec cette classe c'est pour ça qu'on va extraire une autre class formview

        $formView = $form->createView();
        // une petite classe qui permet seulement l'affichage du formulaire et c'est ce qu'on va passer a twig
        return $this->render('product/create.html.twig', ['formView' => $formView]);
    }
}
