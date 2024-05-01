<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $user = new User;
            $user->setAge(mt_rand(10,50))
                 ->setName($this->faker->userName())
                 ->setGender(mt_rand(0,1) == 1 ? "male" : "female");

            $manager->persist($user);
        }

        $manager->flush();
    }
}