<?php

namespace App\Entities\Question;

use App\Models\Question;

class QuestionEloquentEntity implements QuestionEntityInterface
{
    public function __construct(private Question $question)
    {
    }

    public function getId(): int
    {
        return $this->question->id;
    }

    public function getTitle(): string
    {
        return $this->question->title;
    }

    public function getScore(): float
    {
        return floatval($this->question->score);
    }

    public function getIsActive(): int
    {
        return $this->question->is_active;
    }

    public function getOptions(): array
    {
        return json_decode($this->question->options, true);
    }

    public function getQuizId(): int
    {
        return $this->question->quiz_id;
    }
}
