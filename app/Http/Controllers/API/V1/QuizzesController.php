<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Base\BaseAPIController;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuizzesController extends BaseAPIController
{
    public function __construct(private QuizRepositoryInterface $quizRepository)
    {
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/quizzes",
     *     description="Returns all quizzes",
     *     tags={"quizzes"},
     *
     *     @OA\Parameter (
     *          name="page",
     *          in="path",
     *          description="Specify the page number",
     *          required=false,
     *          @OA\Schema (type="integer")
     *     ),
     *
     *     @OA\Parameter (
     *          name="page_size",
     *          in="path",
     *          description="Specify the number of results per page",
     *          required=false,
     *          @OA\Schema (type="integer")
     *     ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="All quizzes",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="All quizzes"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="category_id", type="integer", example="1"),
     *                      @OA\Property(property="title", type="string", example="Quiz Name"),
     *                      @OA\Property(property="description", type="string", example="Quiz Description"),
     *                      @OA\Property(property="start_date", type="date", example="2022-12-8"),
     *                      @OA\Property(property="duration", type="date", example="60"),
     *                      @OA\Property(property="is_active", type="boolean", example="true"),
     *                  )
     *              )
     *          ),
     *     ),
     *
     *)
     *
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'page' => 'nullable|numeric',
            'page_size' => 'nullable|numeric',
        ]);

        $quizzes = $this->quizRepository->paginate($request->page ?? 1, $request->page_size ?? 3, ['title', 'description', 'start_date', 'duration', 'is_active']);

        return $this->respondSuccess('آزمون ها', $quizzes);
    }

    /**
     *
     * @OA\Post (
     *     path="/api/v1/quizzes",
     *     description="Create new quiz",
     *     tags={"quizzes"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Create new quiz",
     *          @OA\JsonContent (
     *                  @OA\Property(property="category_id", type="integer", example="1"),
     *                  @OA\Property(property="title", type="string", example="Quiz Name"),
     *                  @OA\Property(property="description", type="string", example="Quiz Description"),
     *                  @OA\Property(property="start_date", type="date", example="2022-12-8"),
     *                  @OA\Property(property="duration", type="date", example="60"),
     *                  @OA\Property(property="is_active", type="boolean", example="true"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=201,
     *          description="Quiz created",
     *          @OA\JsonContent (
     *                  @OA\Property (property="success", type="boolean", example="true"),
     *                  @OA\Property (property="message", type="string", example="Quiz created"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="category_id", type="integer", example="1"),
     *                      @OA\Property(property="title", type="string", example="Quiz Name"),
     *                      @OA\Property(property="description", type="string", example="Quiz Description"),
     *                      @OA\Property(property="start_date", type="date", example="2022-12-8"),
     *                      @OA\Property(property="duration", type="date", example="60"),
     *                      @OA\Property(property="is_active", type="boolean", example="true"),
     *                  )
     *              )
     *          ),
     *     ),
     *
     * )
     *
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'duration' => 'required|date',
            'is_active' => 'required|bool',
        ]);

        $startDate = Carbon::parse($request->duration);
        $duration = Carbon::parse($request->duration);

        if ($duration->timestamp < $startDate->timestamp) {
            return $this->respondInvalidValiation('تاریخ شروع باید از زمان آزمون بزرگ تر باشد');
        }

        $createdQuiz = $this->quizRepository->create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $startDate->format('Y-m-d'),
            'duration' => $duration,
            'is_active' => $request->is_active
        ]);

        return $this->respondCreated('آزمون ساخته شد', [
            'category_id' => $createdQuiz->getCategoryId(),
            'title' => $createdQuiz->getTitle(),
            'description' => $createdQuiz->getDescription(),
            'start_date' => $createdQuiz->getStartDate(),
            'duration' => Carbon::parse($createdQuiz->getDuration())->timestamp,
            'is_active' => $createdQuiz->getIsActive(),
        ]);
    }

    /**
     *
     * @OA\Delete  (
     *     path="/api/v1/quizzes",
     *     description="Delete new quiz",
     *     tags={"quizzes"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Delete new quiz",
     *          @OA\JsonContent (
     *              @OA\Property (property="id", type="integer", example="1"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="Quiz deleted",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="Quiz deleted"),
     *          ),
     *     ),
     *
     *)
     *
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        if (!$this->quizRepository->find($request->id)) {
            return $this->respondNotFound('آزمون وجود ندارد');
        }

        if (!$this->quizRepository->delete($request->id)) {
            return $this->respondInternalError('آزمون حذف نشد');
        }

        return $this->respondSuccess('آزمون حذف شد', []);
    }

    /**
     *
     * @OA\Put (
     *     path="/api/v1/quizzes",
     *     description="Update a quiz",
     *     tags={"quizzes"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Update a quiz",
     *          @OA\JsonContent (
     *                 @OA\Property(property="id", type="integer", example="1"),
     *                 @OA\Property(property="category_id", type="integer", example="1"),
     *                 @OA\Property(property="title", type="string", example="Quiz Name"),
     *                 @OA\Property(property="description", type="string", example="Quiz Description"),
     *                 @OA\Property(property="start_date", type="date", example="2022-12-8"),
     *                 @OA\Property(property="duration", type="date", example="60"),
     *                 @OA\Property(property="is_active", type="boolean", example="true"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="Quiz updated",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="Quiz updated"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="category_id", type="integer", example="1"),
     *                      @OA\Property(property="title", type="string", example="Quiz Name"),
     *                      @OA\Property(property="description", type="string", example="Quiz Description"),
     *                      @OA\Property(property="start_date", type="date", example="2022-12-8"),
     *                      @OA\Property(property="duration", type="date", example="60"),
     *                      @OA\Property(property="is_active", type="boolean", example="true"),
     *                  )
     *              )
     *          ),
     *     ),
     *
     * )
     *
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'duration' => 'required|date',
            'is_active' => 'required|bool',
        ]);

        $startDate = Carbon::parse($request->duration);
        $duration = Carbon::parse($request->duration);

        if ($duration->timestamp < $startDate->timestamp) {
            return $this->respondInvalidValiation('تاریخ شروع باید از زمان آزمون بزرگ تر باشد');
        }

        try {
            $updatedQuiz = $this->quizRepository->update($request->id, [
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $startDate->format('Y-m-d'),
                'duration' => $duration,
                'is_active' => $request->is_active,
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError('آزمون بروزرسانی نشد');
        }

        return $this->respondSuccess('آزمون بروزرسانی شد', [
            'category_id' => $updatedQuiz->getCategoryId(),
            'title' => $updatedQuiz->getTitle(),
            'description' => $updatedQuiz->getDescription(),
            'start_date' => $updatedQuiz->getStartDate(),
            'duration' => Carbon::parse($updatedQuiz->getDuration())->timestamp,
            'is_active' => $updatedQuiz->getIsActive(),
        ]);
    }

}
