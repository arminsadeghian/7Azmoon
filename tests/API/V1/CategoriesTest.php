<?php

namespace Tests\API\V1;

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

}
