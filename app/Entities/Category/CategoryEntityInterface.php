<?php

namespace App\Entities\Category;

interface CategoryEntityInterface
{
    public function getId(): int;

    public function getName(): string;

    public function getSlug(): string;
}
