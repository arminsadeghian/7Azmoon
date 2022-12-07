<?php

namespace Tests\API\V1;

use App\Consts\AnswerSheetsStatus;
use Carbon\Carbon;
use Tests\TestCase;

class AnswerSheetsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

    /** @test */
    public function ensureWeCanCreateNewAnswerSheet()
    {
        $quiz = $this->createQuiz()[0];

        $answerSheetData = [
            'quiz_id' => $quiz->getId(),
            'answers' => json_encode([
                1 => 3,
                2 => 1,
                3 => 4,
            ]),
            'status' => AnswerSheetsStatus::PASSED,
            'score' => 10,
            'finished_at' => Carbon::now(),
        ];

        $response = $this->call('POST', 'api/v1/answer-sheets', $answerSheetData);

        $responseData = json_decode($response->getContent(), true)['data'];

        $responseData['finished_at'] = Carbon::parse($responseData['finished_at'])->format('Y-m-d H:i:s');
        $answerSheetData['finished_at'] = $answerSheetData['finished_at']->format('Y-m-d H:i:s');

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($answerSheetData['quiz_id'], $responseData['quiz_id']);
        $this->assertEquals($answerSheetData['answers'], $responseData['answers']);
        $this->assertEquals($answerSheetData['status'], $responseData['status']);
        $this->assertEquals($answerSheetData['score'], $responseData['score']);
        $this->assertEquals($answerSheetData['finished_at'], $responseData['finished_at']);
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'quiz_id',
                'answers',
                'status',
                'score',
                'finished_at',
            ],
        ]);
    }

    /** @test */
    public function ensureWeCanDeleteAnswerSheet()
    {
        $answerSheet = $this->createAnswerSheets()[0];

        $response = $this->call('DELETE', 'api/v1/answer-sheets', [
            'id' => $answerSheet->getId(),
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function ensureWeCanGetAnswerSheets()
    {
        $this->createAnswerSheets(30);

        $pageSize = 3;

        $response = $this->call('GET', 'api/v1/answer-sheets', [
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

}
