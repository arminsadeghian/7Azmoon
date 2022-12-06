<?php

namespace Tests;

use App\Repositories\Contracts\CategoryRepositoryInterface;
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

}
