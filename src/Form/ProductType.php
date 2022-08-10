<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "label" => "nom du produit",
                "attr" => [
                    "placeholder" => "Veuillez saisir le nom du produit"
                ]
            ])
            ->add("shortDescription", TextareaType::class, [
                "label" => "description courte",
                "attr" => [
                    "placeholder" => "Veuillez saisir une petite description"
                ]
            ])
            ->add("price", MoneyType::class, [
                "label" => "prix du produit",
                "attr" => [
                    "placeholder" => "Veuillez saisir le prix du produit en €"
                ]
            ])
            ->add("mainPicture", UrlType::class, [
                "label" => "image du produit",
                "attr" => [
                    "placeholder" => "Tapez une Url d'image !"
                ]
            ])
            ->add("mainPicture2", UrlType::class, [
                "label" => "une autre image du produit",
                "attr" => [
                    "placeholder" => "Tapez une Url d'image !"
                ]
            ])
            ->add("category", EntityType::class, [
                "label" => "Category",
                "placeholder" => "--choisir une categorie --",
                "class" => Category::class,
                'choice_label' => function (Category $category) {
                    return $category->getName();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
