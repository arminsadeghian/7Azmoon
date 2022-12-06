<?php

namespace App\Repositories\Eloquent;

use App\Entities\Quiz\QuizEloquentEntity;
use App\Entities\Quiz\QuizEntityInterface;
use App\Models\Quiz;
use App\Repositories\Contracts\QuizRepositoryInterface;

class EloquentQuizRepository extends EloquentBaseRepository implements QuizRepositoryInterface
{
    protected $model = Quiz::class;

    public function create(array $data): QuizEntityInterface
    {
        $createQuiz = parent::create($data);
        return new QuizEloquentEntity($createQuiz);
    }
}
