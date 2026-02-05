<?php

namespace App\EventListener;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsEntityListener(event: Events::postPersist, method: 'logCreatedEvaluation', entity: Evaluation::class)]
final readonly class EvaluationCreatedListener
{

    public function __construct(private LoggerInterface $logger) {
    }
    public function logCreatedEvaluation(Evaluation $evaluation): void
    {
        $user = $evaluation->getAssociatedUser();
        $userEmail = $user->getEmail();
        $evaluationTitle = $evaluation->getChallenge()->getTitle();
        $evaluationValue = $evaluation->getValue();

        $this->logger->info("$userEmail $evaluationTitle $evaluationValue");
    }
}
