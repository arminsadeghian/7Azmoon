<?php

namespace App\Repositories\Eloquent;

use App\Entities\Question\QuestionEloquentEntity;
use App\Entities\Question\QuestionEntityInterface;
use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;

class EloquentQuestionRepository extends EloquentBaseRepository implements QuestionRepositoryInterface
{
    protected $model = Question::class;

    public function create(array $data): QuestionEntityInterface
    {
        $createdQuestion = parent::create($data);
        return new QuestionEloquentEntity($createdQuestion);
    }

    public function update(int $id, array $data): QuestionEntityInterface
    {
        if (!parent::update($id, $data)) {
            throw new \RuntimeException('سوال بروزرسانی نشد');
        }

        return new QuestionEloquentEntity(parent::find($id));
    }

}
