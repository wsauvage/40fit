<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'email' => 'william@ri7.fr',
//                'firstname' => 'William',
//                'lastname' => 'Sauvage',
            ],
            [
                'email' => 'admin@ri7.fr',
//                'firstname' => 'William',
//                'lastname' => 'Sauvage',
            ],
        ];
        foreach ($users as $i => $userData) {
            $user = new User();
            $user->setEmail($userData['email']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $userData['email']
            );

            $user->setPassword($hashedPassword);

            if ($i === 1) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

}
