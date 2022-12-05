<?php

namespace Tests\API\V1;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

    /** @test */
    public function ensureWeCanCreateNewCategory()
    {
        $newCategory = [
            'name' => 'category 1',
            'slug' => 'category-1',
        ];

        $response = $this->call('POST', 'api/v1/categories', $newCategory);

        $this->assertEquals(201, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'name',
                'slug',
            ],
        ]);
    }

    /** @test */
    public function ensureWeCanDeleteCategory()
    {
        $category = $this->createCategory()[0];
        $response = $this->call('DELETE', 'api/v1/categories', [
            'id' => $category->getId(),
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

    private function createCategory(int $count = 1): array
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