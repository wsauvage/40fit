<?php

namespace App\DataFixtures;

use App\Entity\Challenge;
use App\Entity\Evaluation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EvaluationFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $challenges = $manager->getRepository(Challenge::class)->findAll();

        foreach ($users as $user) {
            for ($i = 0; $i < random_int(3, 8); $i++) {
                shuffle($challenges);
                $challenge = $challenges[0];
                $evaluation = new Evaluation()
                    ->setAssociatedUser($user)
                    ->setChallenge($challenge)
                    ->setValue(random_int(0, $challenge->getTargetValue() * 1.2))
                ;
                $manager->persist($evaluation);
            }
        }

        $manager->flush();
    }
}
