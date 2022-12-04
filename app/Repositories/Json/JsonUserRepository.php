<?php

namespace App\Repositories\Json;

use App\Entities\User\UserEntityInterface;
use App\Entities\User\UserJsonEntity;
use App\Repositories\Contracts\UserRepositoryInterface;

class JsonUserRepository extends JsonBaseRepository implements UserRepositoryInterface
{
    protected string $jsonModel = 'users.json';

    public function create(array $data): UserEntityInterface
    {
        $newUser = parent::create($data);
        return new UserJsonEntity($newUser);
    }

    public function find(int $id): UserEntityInterface
    {
        $user = parent::find($id);
        return new UserJsonEntity($user);
    }

}
