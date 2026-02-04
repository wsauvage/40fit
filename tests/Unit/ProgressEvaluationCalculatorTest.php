<?php

namespace Unit;

use App\Entity\Challenge;
use App\Entity\Evaluation;
use App\Service\ProgressEvaluationCalculator;
use LogicException;
use PHPUnit\Framework\TestCase;

class ProgressEvaluationCalculatorTest extends TestCase
{
    public function testBasicCalculation(): void
    {
        $challenge = new Challenge()->setTargetValue(30);
        $evaluation = new Evaluation()->setValue(15)->setChallenge($challenge);

        $evaluationCalculator = new ProgressEvaluationCalculator();
        $result = $evaluationCalculator->calculate($evaluation);

        $this->assertEquals(50, $result);
    }

    public function testBasicCalculationWithDivideByZero(): void
    {
        $challenge = new Challenge()->setTargetValue(0);
        $evaluation = new Evaluation()->setValue(15)->setChallenge($challenge);

        $this->expectException(LogicException::class);

        $evaluationCalculator = new ProgressEvaluationCalculator();
        $evaluationCalculator->calculate($evaluation);
    }
}
