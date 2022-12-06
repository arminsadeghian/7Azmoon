<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Base\BaseAPIController;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Http\Request;

class QuestionsController extends BaseAPIController
{
    public function __construct(private QuestionRepositoryInterface $questionRepository,
                                private QuizRepositoryInterface     $quizRepository)
    {
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'page' => 'nullable|numeric',
            'page_size' => 'nullable|numeric',
        ]);

        $questions = $this->questionRepository->paginate($request->page ?? 1, $request->page_size ?? 10, ['*']);

        return $this->respondSuccess('سوالات', $questions);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'quiz_id' => 'required|numeric',
            'title' => 'required|string',
            'options' => 'required|json',
            'is_active' => 'required|numeric',
            'score' => 'required|numeric',
        ]);

        if (!$this->quizRepository->find($request->quiz_id)) {
            return $this->respondNotFound('آزمون وجود ندارد');
        }

        $question = $this->questionRepository->create([
            'quiz_id' => $request->quiz_id,
            'title' => $request->title,
            'options' => $request->options,
            'score' => $request->score,
            'is_active' => $request->is_active,
        ]);

        return $this->respondCreated('سوال ایجاد شد', [
            'quiz_id' => $question->getQuizId(),
            'title' => $question->getTitle(),
            'options' => json_encode($question->getOptions()),
            'score' => $question->getScore(),
            'is_active' => $question->getIsActive(),
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        if (!$this->questionRepository->find($request->id)) {
            return $this->respondNotFound('سوال وجود ندارد');
        }

        if (!$this->questionRepository->delete($request->id)) {
            return $this->respondInternalError('سوال حذف نشد');
        }

        return $this->respondSuccess('سوال حذف شد', []);
    }
}
