<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        if (!$category) {
            throw $this->createNotFoundException("la categorie demandée n'existe pas");
        }
        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }
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
}
