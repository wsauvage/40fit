<?php

namespace App\DataFixtures;

use App\Entity\Challenge;
use App\Entity\ChallengeCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly ParameterBagInterface $params
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

        $projectDir = $this->params->get('kernel.project_dir');
        $jsonFilePath = $projectDir . '/data/challenges.json';
        $challenges = json_decode(file_get_contents($jsonFilePath), true);

        foreach ($challenges as $challengeData) {

            $categoryName = $challengeData['categoryName'];

           $category =  $manager->getRepository(ChallengeCategory::class)->findOneBy([
               'title' => $categoryName
           ]);

           if (!$category) {
               $category = new ChallengeCategory();
               $category->setTitle($categoryName);
               $manager->persist($category);
               $manager->flush();
           }

           $challenge = new Challenge();
           $challenge->setTitle($challengeData['title']);
           $slug = 'test';
           $challenge->setSlug($slug);
           $challenge->setDescription($challengeData['description']);
           $challenge->setCategory($category);
           $manager->persist($challenge);
        }


        $manager->flush();
    }
}
