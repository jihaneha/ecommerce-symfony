<?php

// pour transformer les prix vu que c en centimes dans ma base de données 

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CentimesTransformer implements DataTransformerInterface
{

    // pour transformer en euros les prix stocker en centimes dans la base données 

    public function transform($value)
    {

        // si la valeur est null en va rien faire sinon on devise par 100

        if ($value === null) {
            return;
        }
        return $value / 100;
    }
    // pour transformer en centimes les prix envoyé par le formulaire en utilise reverseTransform()

    public function reverseTransform($value)
    {
        if ($value === null) {
            return;
        }

        return $value * 100;
    }
}
