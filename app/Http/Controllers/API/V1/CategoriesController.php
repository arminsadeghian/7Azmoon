<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Base\BaseAPIController;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

class CategoriesController extends BaseAPIController
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/categories",
     *     description="Returns all categories",
     *     tags={"categories"},
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
     *          description="All categories",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="All Categories"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="name", type="string", example="Category Name"),
     *                      @OA\Property(property="slug", type="string", example="category-slug"),
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

        $categories = $this->categoryRepository->paginate($request->page ?? 1, $request->page_size ?? 5, ['*']);

        return $this->respondSuccess('دسته بندی ها', $categories);
    }

    /**
     *
     * @OA\Put (
     *     path="/api/v1/categories",
     *     description="Update a category",
     *     tags={"categories"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Update a category",
     *          @OA\JsonContent (
     *              @OA\Property (property="id", type="integer", example="1"),
     *              @OA\Property (property="name", type="string", example="Category Name"),
     *              @OA\Property (property="slug", type="string", example="category-slug"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="Category updated",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="Category updated"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="name", type="string", example="New Category"),
     *                      @OA\Property(property="slug", type="string", example="new-slug"),
     *                  )
     *              )
     *          ),
     *     ),
     *
     * )
     *
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255',
        ]);

        try {
            $updatedCategory = $this->categoryRepository->update($request->id, [
                'name' => $request->name,
                'slug' => $request->slug,
            ]);
        } catch (Exception $e) {
            return $this->respondInternalError('دسته بندی بروزرسانی نشد');
        }

        return $this->respondSuccess('دسته بندی آپدیت شد', [
            'name' => $updatedCategory->getName(),
            'slug' => $updatedCategory->getSlug(),
        ]);
    }

    /**
     *
     * @OA\Post (
     *     path="/api/v1/categories",
     *     description="Create new category",
     *     tags={"categories"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Create new category",
     *          @OA\JsonContent (
     *              @OA\Property (property="name", type="string", example="Category Name"),
     *              @OA\Property (property="slug", type="string", example="category-slug"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=201,
     *          description="Category created",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="All Categories"),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example="1"),
     *                      @OA\Property(property="name", type="string", example="Category Name"),
     *                      @OA\Property(property="slug", type="string", example="category-slug"),
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
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255',
        ]);

        $createdCategory = $this->categoryRepository->create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return $this->respondCreated('دسته بندی ایجاد شد', [
            'id' => $createdCategory->getId(),
            'name' => $createdCategory->getName(),
            'slug' => $createdCategory->getSlug(),
        ]);
    }

    /**
     *
     * @OA\Delete  (
     *     path="/api/v1/categories",
     *     description="Delete a category",
     *     tags={"categories"},
     *
     *     @OA\RequestBody (
     *          required=true,
     *          description="Delete a category",
     *          @OA\JsonContent (
     *              @OA\Property (property="id", type="integer", example="1"),
     *          ),
     *      ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="Category deleted",
     *          @OA\JsonContent (
     *              @OA\Property (property="success", type="boolean", example="true"),
     *              @OA\Property (property="message", type="string", example="Category deleted"),
     *          ),
     *     ),
     *
     *)
     *
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        if (!$this->categoryRepository->find($request->id)) {
            return $this->respondNotFound('دسته بندی وجود ندارد');
        }

        if (!$this->categoryRepository->delete($request->id)) {
            return $this->respondInternalError('دسته بندی حذف نشد');
        }

        $this->categoryRepository->delete($request->id);

        return $this->respondSuccess('دسته بندی حذف شد', []);
    }

}
