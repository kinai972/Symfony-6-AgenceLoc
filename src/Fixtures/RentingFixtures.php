<?php

namespace App\Fixtures;

use App\Entity\Renting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RentingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 7; $i++) {
            $renting = new Renting();

            $renting->setStartsAt(
                $faker->dateTimeBetween(startDate: '-11 months', endDate: '-6 months')
            )
                ->setEndsAt($faker->dateTimeBetween(startDate: '-5 months', endDate: '+2 months'))
                ->setTotalPrice($faker->randomFloat(nbMaxDecimals: 2, min: 15000, max: 50000))
                ->setUser($this->getReference(name: 'user-' . rand(1, 7)))
                ->setVehicle($this->getReference(name: 'vehicle-' . rand(1, 7)));

            $manager->persist($renting);

            $this->addReference(name: "renting-$i", object: $renting);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VehicleFixtures::class,
            UserFixtures::class,
        ];
    }
}
