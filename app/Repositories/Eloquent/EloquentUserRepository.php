<?php

namespace App\Repositories\Eloquent;

use App\Entities\User\UserEloquentEntity;
use App\Entities\User\UserEntityInterface;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    protected $model = User::class;

    public function create(array $data): UserEntityInterface
    {
        $newUser = parent::create($data);
        return new UserEloquentEntity($newUser);
    }

    public function update(int $id, array $data): UserEntityInterface
    {
        $updatedUser = parent::update($id, $data);

        if (!$updatedUser) {
            throw new \Exception('کاربر آپدیت نشد');
        }

        return new UserEloquentEntity(parent::find($id));
    }

}
