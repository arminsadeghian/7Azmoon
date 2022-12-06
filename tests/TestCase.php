<?php

namespace Tests;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Carbon\Carbon;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected function createCategory(int $count = 1): array
    {
        $categories = [];

        $categoryRepository = $this->app->make(CategoryRepositoryInterface::class);

        $categoryData = [
            'name' => 'category1',
            'slug' => 'category-1',
        ];

        foreach (range(0, $count) as $item) {
            $categories[] = $categoryRepository->create($categoryData);
        }

        return $categories;
    }

    protected function createQuiz(int $count = 1, array $data = []): array
    {
        $quizRepository = $this->app->make(QuizRepositoryInterface::class);

        $category = $this->createCategory()[0];

        $startDate = Carbon::now()->addDay();
        $duration = Carbon::now()->addDay();

        $quizData = empty($data) ? [
            'category_id' => $category->getId(),
            'title' => 'Quiz 1',
            'description' => 'this is a test quiz',
            'duration' => $duration->addMinutes(30),
            'start_date' => $startDate,
        ] : $data;

        $quizzes = [];

        foreach (range(0, $count) as $item) {
            $quizzes[] = $quizRepository->create($quizData);
        }

        return $quizzes;
    }

}
