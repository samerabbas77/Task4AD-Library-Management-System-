<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Category;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Service\Api\CategoryServices;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateeRequest;

class CategoryController extends Controller
{
    use ResponseTrait;
    protected $categoryService;
    public function __construct(CategoryServices $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    //............................................................................
    //............................................................................
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getAllCategory = $this->categoryService->getAllCategory();
        if(!empty($getAllCategory)){
        return $this->successResponse($getAllCategory,"All Data fetching successfully");
        }else{
            return response()->json("There are no data");
        }

    }
    //............................................................................
    //............................................................................
    /**
     * store a category
     * @param \App\Http\Requests\Category\StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
      $data = $request->validated();  
      $category = $this->categoryService->storeService($data);

      return $this->successResponse($category,"new Category store successfully");

    }

    //............................................................................
    //............................................................................
    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category = $this->categoryService->showService($category);
         return $this->successResponse($category,"Showing Category successfully");

    }

    //............................................................................
    //............................................................................

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateeRequest $request, Category $category)
    {
        $data = $request->validated();
        $new_cat = $this->categoryService->updateService($data,$category);
        return $this->successResponse($new_cat,"Category updated successfully");
    }
    
    //............................................................................
    //............................................................................

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category = $this->categoryService->destroyService($category);

        return $this->successResponse($category,"Deleting category Succefully");
    }



}
