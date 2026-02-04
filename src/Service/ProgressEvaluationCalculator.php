<?php

namespace App\Service;

use App\Entity\Evaluation;
use Symfony\Component\Form\Exception\LogicException;

class ProgressEvaluationCalculator
{
    public function calculate(Evaluation $evaluation): float
    {
        $challenge = $evaluation->getChallenge();
        if ($challenge->getTargetValue() === 0) {
            throw new LogicException('Challenge target value can\'t be zero');
        }

        return $evaluation->getValue() / $challenge->getTargetValue() * 100;
    }
}
