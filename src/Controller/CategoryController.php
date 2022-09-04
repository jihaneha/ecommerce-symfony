<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    protected $categoryRepository;
    //on ne peut pas appeler CategoryRepository dans la fonction vu qu'elle est pas reliser à une route

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    // afficher le menu directement dans twig

    public function renderMenuList()
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/_menu.html.twig', [
            'categories' => $categories
        ]);
    }
    #[Route('/admin/category/create', name: 'category_create')]

    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();
        return $this->render('category/create.html.twig', [
            'formView' => $formView
        ]);
    }
    #[Route('/admin/category/{id}/edit', name: 'category_edit')]
    // #[IsGranted('ROLE_ADMIN', message: "Vous n'avez pas le droit d'accéder à cette ressource")]
    #[IsGranted('CAN_EDIT', subject: "id")]
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        //si l'utilisateur n'a pas le role admin , il ne peut pas modifier les categories
        // $user = $this->getUser();
        // if ($user === null) {
        //     return $this->redirectToRoute('security_login');
        // }

        // if ($this->isGranted("ROLE_ADMIN" === false)) {
        //     throw new AccessDeniedHttpException("Vous n'avez pas le droit d'accéder à cette ressource");
        // }

        //on peut aussi utiliser 

        // $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Vous n'avez pas le droit d'accéder à cette ressource");

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new NotFoundHttpException("cette catégorie n'existe pas");
        }

        // $this->isGranted('CAN_EDIT', $category);

        // $this->denyAccessUnlessGranted('CAN_EDIT', $category, "vous n'etes pas le propriétaire de cette categorie");

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        $formView = $form->createView();

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
        ]);
    }
}
