<?php

namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 7; $i++) {
            $user = new User();
            $lastName = $faker->lastName();
            $firstName = $faker->firstNameMale();
            $gender = 'm';

            if (1 === random_int(min: 0, max: 1)) {
                $firstName = $faker->firstNameMale();
                $gender = 'f';
            }

            $user->setUsername($faker->userName())
                ->setEmail("$firstName.$lastName@mail.com")
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setGender($gender)
                ->setPassword(
                    $this->hasher->hashPassword(user: $user, plainPassword: 1234)
                );

            $manager->persist($user);

            $this->addReference(name: "user-$i", object: $user);
        }

        $manager->flush();
    }
}
