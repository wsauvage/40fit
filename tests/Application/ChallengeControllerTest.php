<?php

namespace App\Tests\Application;

use App\Entity\Challenge;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChallengeControllerTest extends WebTestCase
{
    #[DataProvider('provideTestCases')]
    public function testCreateNewEvaluationOnChallenge(int $value): void
    {
        $client = static::createClient();
        /** @var ObjectManager $manager */
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $challenge = $manager->getRepository(Challenge::class)->findOneBy([]);
        $user = $manager->getRepository(User::class)->findOneBy([]);

        foreach ($challenge->getEvaluations() as $evaluation) {
            $manager->remove($evaluation);
        }

        $manager->flush();

        $client->loginUser($user);
        $id = $challenge->getId();
        $client->request('GET', "/user/challenge/$id/evaluations/create");
        $this->assertResponseIsSuccessful();
        $client->submitForm('Enregistrer', [
            'evaluation[value]' => $value
        ]);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $crawler = $crawler->filter('.test-evaluation-value');
        $currentValue = (int) $crawler->text();
        $this->assertEquals($value, $currentValue);
    }

    public static function provideTestCases(): array
    {
        return [
            [13],
            [16],
            [9],
        ];
    }
}
