<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function store()
    {
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
