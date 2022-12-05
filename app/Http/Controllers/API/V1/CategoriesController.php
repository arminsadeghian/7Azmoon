<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Base\BaseAPIController;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoriesController extends BaseAPIController
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
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

}