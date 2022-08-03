<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category')]
    public function category($slug, CategoryRepository $CategoryRepository): Response
    {
        $category = $CategoryRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }
    #[Route('/{category_slug}/{slug}', name: 'product_show')]

    public function show($slug, ProductRepository $ProductRepository)
    {
        $product = $ProductRepository->findOneBy([
            'slug' => $slug
        ]);
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
