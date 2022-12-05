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

    public function index(Request $request)
    {
        $this->validate($request, [
            'page' => 'nullable|numeric',
            'page_size' => 'nullable|numeric',
        ]);

        $categories = $this->categoryRepository->paginate($request->page ?? 1, $request->page_size ?? 5, ['*']);

        return $this->respondSuccess('دسته بندی ها', $categories);
    }

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
            'name' => $createdCategory->getName(),
            'slug' => $createdCategory->getSlug(),
        ]);
    }

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

}
