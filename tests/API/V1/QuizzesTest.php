<?php

namespace Tests\API\V1;

use Carbon\Carbon;
use Tests\TestCase;

class QuizzesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

    /** @test */
    public function ensureWeCanCreateNewQuiz()
    {
        $category = $this->createCategory()[0];

        $startDate = Carbon::now()->addDay();
        $duration = Carbon::now()->addDay();

        $quizData = [
            'category_id' => $category->getId(),
            'title' => 'Quiz 1',
            'description' => 'Description for quiz',
            'start_date' => $startDate,
            'duration' => $duration->addMinutes(60),
            'is_active' => true,
        ];

        $response = $this->call('POST', 'api/v1/quizzes', $quizData);
        $responseData = json_decode($response->getContent(), true)['data'];
        $quizData['start_date'] = $quizData['start_date']->format('Y-m-d');
        $quizData['duration'] = $quizData['duration']->format('Y-m-d H:i:s');

        $this->assertEquals(201, $response->getStatusCode());
        $this->seeInDatabase('quizzes', $quizData);
        $this->assertEquals($quizData['title'], $responseData['title']);
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'category_id',
                'title',
                'description',
                'start_date',
                'duration',
            ],
        ]);
    }

    /** @test */
    public function ensureWeCanDeleteQuiz()
    {
        $quiz = $this->createQuiz()[0];

        $response = $this->call('DELETE', 'api/v1/quizzes', [
            'id' => $quiz->getId(),
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function ensureWeCanGetQuizzes()
    {
        $this->createQuiz(30);

        $pageSize = 3;

        $response = $this->call('GET', 'api/v1/quizzes', [
            'page' => 1,
            'page_size' => $pageSize,
        ]);

        $data = json_decode($response->getContent(), true);

        $this->assertEquals($pageSize, count($data['data']));
        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function ensureWeCanUpdateQuiz()
    {
        $quiz = $this->createQuiz()[0];
        $category = $this->createCategory()[0];
        $startDate = Carbon::now()->addDay();
        $duration = Carbon::now()->addDay();

        $quizData = [
            'id' => $quiz->getId(),
            'category_id' => $category->getId(),
            'title' => 'Quiz Updated',
            'description' => 'Updated description',
            'start_date' => $startDate,
            'duration' => $duration->addMinutes(60),
            'is_active' => true,
        ];

        $response = $this->call('PUT', 'api/v1/quizzes', $quizData);

        $data = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals($data['title'], $quizData['title']);
        $this->assertEquals($data['description'], $quizData['description']);
        $this->assertEquals($data['is_active'], $quizData['is_active']);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'category_id',
                'title',
                'description',
                'duration',
                'start_date',
                'is_active'
            ],
        ]);
    }

}
