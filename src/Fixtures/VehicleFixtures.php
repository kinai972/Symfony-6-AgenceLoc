<?php

namespace App\Fixtures;

use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VehicleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 7; $i++) {
            $vehicle = new Vehicle();

            $vehicle->setTitle(ucfirst($faker->words(mt_rand(2, 4), true)))
                ->setMake(ucfirst($faker->word()))
                ->setModel(ucfirst($faker->words(mt_rand(1, 2), true)))
                ->setDescription($faker->paragraphs(mt_rand(1, 3), true))
                ->setImage("vehicle-$i.jpg")
                ->setDailyPrice($faker->randomFloat(nbMaxDecimals: 2, min: 1000, max: 5000));

            $manager->persist($vehicle);

            $this->addReference(name: "vehicle-$i", object: $vehicle);
        }

        $manager->flush();
    }
}
