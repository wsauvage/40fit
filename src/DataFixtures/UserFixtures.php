<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $admin = new User()
            ->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@admin.fr');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin@admin.fr'));
        $manager->persist($admin);

        for ($i = 0; $i < 4; $i++) {
            $email = $faker->email();
            $user = new User()->setEmail($email);
            $user->setPassword($this->passwordHasher->hashPassword($user, $email));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
