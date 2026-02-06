<?php

namespace App\Tests\Integration;

use App\Entity\Challenge;
use App\Entity\ChallengeCategory;
use App\Repository\ChallengeRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChallengeRepositoryTest extends KernelTestCase
{
    #[DataProvider("provider")]
    public function testFindByCategory(
        ?string $categoryTitle,
        int $expectedChallengeCount,
    ): void {
        $container = $this->getContainer();

        /** @var ObjectManager $manager */
        $manager = $container->get("doctrine")->getManager();
        $categoryRepo = $manager->getRepository(ChallengeCategory::class);
        $category = $categoryRepo->findOneBy(["title" => $categoryTitle]);

        /** @var ChallengeRepository $challengeRepo */
        $challengeRepo = $manager->getRepository(Challenge::class);
        $categories = $challengeRepo->findByCategory($category);

        $this->assertCount($expectedChallengeCount, $categories);
    }

    public static function provider(): array
    {
        return [
            ["Force Corporelle", 5],
            ["Endurance Cardiovasculaire", 5],
            ["MSEC", 5],
            [null, 15],
            ["", 0],
            ["slhkjfboaj", 0],
        ];
    }
}
