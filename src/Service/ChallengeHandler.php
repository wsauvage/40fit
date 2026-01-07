<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ChallengeHandler
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function getChallenges() : array {
        $projectDir = $this->params->get('kernel.project_dir');
        $jsonFilePath = $projectDir . '/data/challenges.json';
        $challenges = json_decode(file_get_contents($jsonFilePath), true);
        return $challenges;
    }

    public function getChallengeById(int $id): ?array {

        $challenges = $this->getChallenges();

        foreach ($challenges as $challenge) {
            if ($challenge['id'] === $id) {
                return $challenge;
            }
        }

        $this->logger->warning("Challenge #{$id} introuvable dans le fichier JSON.");

        return null;
    }


}
