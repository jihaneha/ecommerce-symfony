<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Purchase;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {

        //creer des utilisateurs virtuels

        $faker = Factory::create('fr_FR');

        $admin = new User;

        $hash = $this->encoder->hashPassword($admin, "password");
        $admin->setEmail("admin@gmail.com")
            ->setFullName("Admin")
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $users = [];

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);


            $users[] = $user;
            $manager->persist($user);
        }

        //creer des commandes virtuelles
        for (
            $p = 0;
            $p < mt_rand(20, 40);
            $p++
        ) {
            $purchase = new Purchase;
            $purchase->setFullName($faker->name)
                ->setAdress($faker->streetAddress)
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000));

            if ($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }
            $manager->persist($purchase);
        }


        $manager->flush();
    }
}
