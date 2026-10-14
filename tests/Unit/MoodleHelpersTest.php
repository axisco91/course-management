<?php

namespace Tests\Unit;

use App\Helpers\MoodleHelpers;
use ReflectionMethod;
use Tests\TestCase;

class MoodleHelpersTest extends TestCase
{
    private function callClassifier(string $method, string $type, string $name): bool
    {
        $reflection = new ReflectionMethod(MoodleHelpers::class, $method);
        $reflection->setAccessible(true);

        return (bool) $reflection->invoke(null, $type, $name);
    }

    public function testItSeparatesScormUnitsAndEvaluations(): void
    {
        $this->assertTrue($this->callClassifier('isContentUnit', 'scorm', 'Unidad 1'));
        $this->assertFalse($this->callClassifier('isEvaluationActivity', 'scorm', 'Unidad 1'));

        $this->assertTrue($this->callClassifier('isEvaluationActivity', 'scorm', 'Autoevaluación 1'));
        $this->assertFalse($this->callClassifier('isContentUnit', 'scorm', 'Autoevaluación 1'));
        $this->assertFalse($this->callClassifier('isEvaluationActivity', 'assign', 'Tarea 1'));
    }

    public function testItDetectsTheFinalEvaluationAsScormOrQuiz(): void
    {
        $this->assertTrue($this->callClassifier('isFinalEvaluation', 'scorm', 'Evaluación Final'));
        $this->assertTrue($this->callClassifier('isFinalEvaluation', 'quiz', 'Cuestionario final'));
        $this->assertTrue($this->callClassifier('isFinalEvaluation', 'assign', 'Examen Final'));
        $this->assertFalse($this->callClassifier('isEvaluationActivity', 'scorm', 'Evaluación Final'));
        $this->assertFalse($this->callClassifier('isEvaluationActivity', 'quiz', 'Cuestionario final'));
    }

    public function testNativeQuizIsAnActivityAndOrdinaryAssignmentIsATask(): void
    {
        $reflection = new ReflectionMethod(MoodleHelpers::class, 'inferredModuleType');
        $reflection->setAccessible(true);

        $this->assertSame('activity', $reflection->invoke(null, 'quiz', 'Prueba tema 2'));
        $this->assertSame('task', $reflection->invoke(null, 'assign', 'Tarea'));
        $this->assertSame('final_evaluation', $reflection->invoke(null, 'assign', 'Examen Final'));
    }

    public function testItDoesNotCountTheSatisfactionSurveyAsAUnitOrEvaluation(): void
    {
        $this->assertFalse($this->callClassifier(
            'isContentUnit',
            'scorm',
            'Encuesta de Satisfacción y Propuesta de Mejora'
        ));
        $this->assertFalse($this->callClassifier(
            'isEvaluationActivity',
            'scorm',
            'Encuesta de Satisfacción y Propuesta de Mejora'
        ));
    }

    public function testSavedCmidClassificationOverridesTheModuleName(): void
    {
        $reflection = new ReflectionMethod(MoodleHelpers::class, 'resolvedModuleType');
        $reflection->setAccessible(true);

        $this->assertSame('activity', $reflection->invoke(
            null,
            42,
            'scorm',
            'Unidad didáctica 2',
            ['42' => 'activity']
        ));
        $this->assertSame('unit', $reflection->invoke(
            null,
            43,
            'scorm',
            'Contenido sin numeración',
            []
        ));
    }
}
