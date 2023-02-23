<?php

namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{
    private SluggerInterface $slugger;
    private UserPasswordHasherInterface $hasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher)
    {
        $this->slugger = $slugger;
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $this->prepare(user: $admin, email: 'admin@mail.com', roles: ['ROLE_ADMIN']);
        $manager->persist($admin);

        $user = new User();
        $this->prepare(user: $admin, email: 'user@mail.com');
        $manager->persist($user);

        for ($i = 1; $i <= 7; $i++) {
            $user = new User();

            $this->prepare(user: $user);

            $manager->persist($user);

            $this->addReference(name: "user-$i", object: $user);
        }

        $manager->flush();
    }

    public function prepare(User $user, string $email = null, array $roles = ['ROLE_USER'])
    {
        $faker = \Faker\Factory::create('fr_FR');

        $lastName = $faker->lastName();
        $firstName = $faker->firstNameMale();
        $gender = 'm';

        if (1 === random_int(min: 0, max: 1)) {
            $firstName = $faker->firstNameFemale();
            $gender = 'f';
        }

        $user->setUsername($faker->userName())
            ->setEmail(
                $email ?
                    $email :
                    $this->slugger->slug(strtolower("$firstName.$lastName")) . '@mail.com'
            )
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setGender($gender)
            ->setRoles($roles)
            ->setPassword(
                $this->hasher->hashPassword(user: $user, plainPassword: 12345)
            );
    }
}
