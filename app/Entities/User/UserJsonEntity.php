<?php

namespace App\Entities\User;

class UserJsonEntity implements UserEntityInterface
{

    public function __construct(private array|null $user)
    {
    }

    public function getId(): int
    {
        return $this->user['id'];
    }

    public function getFullName(): string
    {
        return $this->user['full_name'];
    }

    public function getEmail(): string
    {
        return $this->user['email'];
    }

    public function getMobile(): string
    {
        return $this->user['mobile'];
    }

    public function getPassword(): string
    {
        return $this->user['password'];
    }
}
