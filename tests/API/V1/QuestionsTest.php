<?php

namespace Tests\API\V1;

use App\Consts\QuestionStatus;
use Tests\TestCase;

class QuestionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    /** @test */
    public function ensureWeCanCreateNewQuestion()
    {
        $quiz = $this->createQuiz()[0];
        $questionData = [
            'quiz_id' => $quiz->getId(),
            'title' => 'What is PHP?',
            'options' => json_encode([
                1 => ['text' => 'PHP is a car', 'is_correct' => 0],
                2 => ['text' => 'PHP is a programming language', 'is_correct' => 1],
                3 => ['text' => 'PHP is a animal', 'is_correct' => 0],
                4 => ['text' => 'PHP is a os', 'is_correct' => 0],
            ]),
            'score' => 5,
            'is_active' => QuestionStatus::ACTIVE,
        ];

        $response = $this->call('POST', 'api/v1/questions', $questionData);

        $responseData = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertEquals($questionData['quiz_id'], $responseData['quiz_id']);
        $this->assertEquals($questionData['title'], $responseData['title']);
        $this->assertEquals($questionData['options'], $responseData['options']);
        $this->assertEquals($questionData['score'], $responseData['score']);
        $this->assertEquals($questionData['is_active'], $responseData['is_active']);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'quiz_id',
                'title',
                'options',
                'is_active',
                'score',
            ],
        ]);
    }

}
