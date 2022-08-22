<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class, [
                "label" => "nom du produit",
                "attr" => [
                    "placeholder" => "Veuillez saisir le nom du produit"
                ],
                "required" => false,
                "constraints" => new NotBlank(['message' => "le nom du produit ne peut pas etre vide!"])
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
                ],
                "divisor" => 100
            ])
            ->add("mainpicture", FileType::class, [
                "label" => "image du produit",
                "mapped" => false,

            ])
            ->add("mainpicture2", FileType::class, [
                "label" => "une autre image du produit",
                "mapped" => false,
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
            'data_class' => Product::class
        ]);
    }
}
