<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;

class UsersController extends Controller
{

//    private UserRepositoryInterface $userRepository;
//
//    public function __construct(UserRepositoryInterface $userRepository)
//    {
//        $this->userRepository = $userRepository;
//    }

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function store()
    {
//        $this->userRepository->create();

        return response()->json([
            'success' => true,
            'message' => 'کاربر ایجاد شد',
            'data' => [
                'full_name' => 'Armin Sadeghian',
                'email' => 'armin@gmail.com',
                'mobile' => '09038884841',
                'password' => '123456',
            ],
        ])->setStatusCode(201);
    }
}
