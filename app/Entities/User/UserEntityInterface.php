<?php

namespace App\Entities\User;

interface UserEntityInterface
{
    public function getId(): int;

    public function getFullName(): string;

    public function getEmail(): string;

    public function getMobile(): string;

    public function getPassword(): string;
}
