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

    /**
     *
     * @OA\Get (
     *     path="/api/v1/users",
     *     description="Returns all users",
     *     tags={"users"},
     *
     *     @OA\Parameter (
     *          name="page",
     *          in="path",
     *          description="Specify the page number",
     *          required=false,
     *          @OA\Schema (type="integer")
     *     ),
     *
     *     @OA\Parameter (
     *          name="page_size",
     *          in="path",
     *          description="Specify the number of results per page",
     *          required=false,
     *          @OA\Schema (type="integer")
     *     ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="All users",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="All Users"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="full_name", type="string", example="Armin Sadeghian"),
     *                      @OA\Property(property="email", type="string", example="armin@gmail.com"),
     *                      @OA\Property(property="mobile", type="string", example="09111111111"),
     *                      @OA\Property(property="password", type="string", example="123456"),
     *                  )
     *              )
     *          ),
     *     ),
     *
     *)
     *
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'page' => 'nullable|numeric',
            'page_size' => 'nullable|numeric',
        ]);

        $users = $this->userRepository->paginate($request->page ?? 1, $request->page_size ?? 20);

        return $this->respondSuccess('کاربران', $users);
    }

    /**
     *
     * @OA\Post (
     *     path="/api/v1/users",
     *     description="Create new user",
     *     tags={"users"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Create new user",
     *          @OA\JsonContent (
     *                 @OA\Property(property="full_name", type="string", example="Armin Sadeghian"),
     *                 @OA\Property(property="email", type="string", example="armin@gmail.com"),
     *                 @OA\Property(property="mobile", type="string", example="09111111111"),
     *                 @OA\Property(property="password", type="string", example="123456"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=201,
     *          description="User created",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="User created"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                          @OA\Property(property="id", type="integer", example="1"),
     *                          @OA\Property(property="full_name", type="string", example="Armin Sadeghian"),
     *                          @OA\Property(property="email", type="string", example="armin@gmail.com"),
     *                          @OA\Property(property="mobile", type="string", example="09111111111"),
     *                          @OA\Property(property="password", type="string", example="123456"),
     *                  )
     *              )
     *          ),
     *     ),
     *
     * )
     *
     */
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

    /**
     *
     * @OA\Put (
     *     path="/api/v1/users",
     *     description="Update user information",
     *     tags={"users"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Update user information",
     *          @OA\JsonContent (
     *              @OA\Property(property="id", type="integer", example="1"),
     *              @OA\Property(property="full_name", type="string", example="Armin Sadeghian"),
     *              @OA\Property(property="email", type="string", example="armin@gmail.com"),
     *              @OA\Property(property="mobile", type="string", example="09111111111"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="User information updated",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="User information updated"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="full_name", type="string", example="Armin Sadeghian"),
     *                      @OA\Property(property="email", type="string", example="armin@gmail.com"),
     *                      @OA\Property(property="mobile", type="string", example="09111111111"),
     *                  )
     *              )
     *          ),
     *     ),
     *
     * )
     *
     */
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

    /**
     *
     * @OA\Put (
     *     path="/api/v1/users/change-password",
     *     description="Update user password",
     *     tags={"users"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Update user password",
     *          @OA\JsonContent (
     *              @OA\Property(property="id", type="integer", example="1"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *              @OA\Property(property="password_repeat", type="string", example="123456"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="User password updated",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="User password updated"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="full_name", type="string", example="Armin Sadeghian"),
     *                      @OA\Property(property="email", type="string", example="armin@gmail.com"),
     *                      @OA\Property(property="mobile", type="string", example="09111111111"),
     *                      @OA\Property(property="password", type="string", example="123456"),
     *                  )
     *              )
     *          ),
     *     ),
     *
     * )
     *
     */
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

    /**
     *
     * @OA\Delete  (
     *     path="/api/v1/users",
     *     description="Delete a user",
     *     tags={"users"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Delete a user",
     *          @OA\JsonContent (
     *              @OA\Property (property="id", type="integer", example="1"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="User deleted",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="User deleted"),
     *          ),
     *     ),
     *
     *)
     *
     */
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
