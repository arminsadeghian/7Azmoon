<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Base\BaseAPIController;
use App\Repositories\Contracts\AnswerSheetRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Http\Request;

class AnswerSheetsController extends BaseAPIController
{
    public function __construct(private AnswerSheetRepositoryInterface $answerSheetRepository,
                                private QuizRepositoryInterface        $quizRepository)
    {
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'quiz_id' => 'required|numeric',
            'answers' => 'required|json',
            'status' => 'required|numeric',
            'score' => 'required|numeric',
            'finished_at' => 'required|date',
        ]);

        if (!$this->quizRepository->find($request->quiz_id)) {
            return $this->respondNotFound('آزمون یافت نشد');
        }

        $answerSheet = $this->answerSheetRepository->create([
            'quiz_id' => $request->quiz_id,
            'answers' => $request->answers,
            'status' => $request->status,
            'score' => $request->score,
            'finished_at' => $request->finished_at,
        ]);

        return $this->respondCreated('پاسخ نامه ایجاد شد', [
            'quiz_id' => $answerSheet->getQuizId(),
            'answers' => json_encode($answerSheet->getAnswers()),
            'status' => $answerSheet->getStatus(),
            'score' => $answerSheet->getScore(),
            'finished_at' => $answerSheet->getFinishedAt(),
        ]);
    }

}
