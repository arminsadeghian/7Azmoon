<?php

namespace App\Repositories\Eloquent;

use App\Entities\Category\CategoryEloquentEntity;
use App\Entities\Category\CategoryEntityInterface;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepositoryInterface
{
    protected $model = Category::class;

    public function create(array $data): CategoryEntityInterface
    {
        $createdCategory = parent::create($data);
        return new CategoryEloquentEntity($createdCategory);
    }

    public function update(int $id, array $data): CategoryEntityInterface
    {
        if (!parent::update($id, $data)) {
            throw new \RuntimeException('دسته بندی بروزرسانی نشد');
        }

        return new CategoryEloquentEntity(parent::find($id));
    }

}
