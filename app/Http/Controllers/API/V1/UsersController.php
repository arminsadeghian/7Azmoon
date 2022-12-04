<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Base\BaseAPIController;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

class UsersController extends BaseAPIController
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'page' => 'nullable|numeric',
            'page_size' => 'nullable|numeric',
        ]);

        $users = $this->userRepository->paginate($request->page ?? 1, $request->page_size ?? 20);

        return $this->respondSuccess('کاربران', $users);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'password' => 'required',
        ]);

        $newUser = $this->userRepository->create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => app('hash')->make($request->password),
        ]);

//        return $newUser->getEmail();

        return $this->respondCreated('کاربر ایجاد شد', [
            'full_name' => $newUser->getFullName(),
            'email' => $newUser->getEmail(),
            'mobile' => $newUser->getMobile(),
            'password' => $newUser->getPassword(),
        ]);
    }

    public function updateInformation(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
        ]);

        $user = $this->userRepository->update($request->id, [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ]);

        return $this->respondSuccess('کاربر آپدیت شد', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'password' => 'min:6|required_with:password_repeat|same:password_repeat',
            'password_repeat' => 'min:6',
        ]);

        try {
            $user = $this->userRepository->update($request->id, [
                'password' => app('hash')->make($request->password),
            ]);
        } catch (Exception $e) {
            return $this->respondInternalError('کاربر آپدیت نشد');
        }

        return $this->respondSuccess('پسورد کاربر آپدیت شد', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile(),
        ]);

    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        if (!$this->userRepository->find($request->id)) {
            return $this->respondNotFound('کاربری با این آیدی پیدا نشد');
        }

        if (!$this->userRepository->delete($request->id)) {
            return $this->respondInternalError('خطایی وجود دارد لطفا دوباره امتحان کنید');
        }

        return $this->respondSuccess('کاربر حذف شد', []);
    }

}
