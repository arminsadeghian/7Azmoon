<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Base\BaseAPIController;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UsersController extends BaseAPIController
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'password' => 'required',
        ]);

        $this->userRepository->create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => app('hash')->make($request->password),
        ]);

        return $this->respondCreated('کاربر ایجاد شد', [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password,
        ]);

//        return response()->json([
//            'success' => true,
//            'message' => 'کاربر ایجاد شد',
//            'data' => [
//                'full_name' => $request->full_name,
//                'email' => $request->email,
//                'mobile' => $request->mobile,
//                'password' => $request->password,
//            ],
//        ])->setStatusCode(201);

    }

    public function updateInformation(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
        ]);

        $this->userRepository->update($request->id, [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        return $this->respondSuccess('کاربر آپدیت شد', [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'password' => 'min:6|required_with:password_repeat|same:password_repeat',
            'password_repeat' => 'min:6',
        ]);

        $this->userRepository->update($request->id, [
            'password' => app('hash')->make($request->password),
        ]);

        return $this->respondSuccess('پسورد کاربر آپدیت شد', [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $this->userRepository->delete($request->id);

        return $this->respondSuccess('کاربر حذف شد', []);
    }

}
