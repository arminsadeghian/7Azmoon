<?php

namespace App\Repositories\Eloquent;

use App\Entities\AnswerSheet\AnswerSheetEloquentEntity;
use App\Entities\AnswerSheet\AnswerSheetEntityInterface;
use App\Models\AnswerSheet;
use App\Repositories\Contracts\AnswerSheetRepositoryInterface;

class EloquentAnswerSheetRepository extends EloquentBaseRepository implements AnswerSheetRepositoryInterface
{
    protected $model = AnswerSheet::class;

    public function create(array $data): AnswerSheetEntityInterface
    {
        $createdAnswerSheet = parent::create($data);
        return new AnswerSheetEloquentEntity($createdAnswerSheet);
    }
}
