<?php

namespace App\Entities\Question;

interface QuestionEntityInterface
{
    public function getId(): int;

    public function getTitle(): string;

    public function getScore(): float;

    public function getIsActive(): int;

    public function getOptions(): array;

    public function getQuizId(): int;
}
