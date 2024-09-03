<?php

namespace App\Service\Api;

use App\Http\Resources\CategoryResources;
use Exception;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryServices
{
    public function getAllCategory()
    {
        try {
            $categories = Category::paginate(10); 
            //return $categories;
            return   CategoryResources::collection($categories);  
        } catch (Exception $e) {
           return $this->handleException($e,"Sothing   went Rong while Fithing data");  
        }

    }
    /**
     * store a category 
     * @param mixed $data
     * @return CategoryResources|mixed|\Illuminate\Http\JsonResponse
     */
    public function storeService($data)
    {
        try {
                $category = Category::create($data);
                return new CategoryResources($category);
        } catch (Exception $e) {
            return $this->handleException($e,"Sothing  went Rong while Storing data");  

        }
    }
    /**
     * update the name of the category
     * @param mixed $data
     * @param mixed $category
     * @return CategoryResources|mixed|\Illuminate\Http\JsonResponse
     */
    public function updateService($data, $category)
    {
        try {
               $category->name = $data['name'];
               $category->save();
               return new CategoryResources($category); 
        } catch (Exception $e) {
            return $this->handleException($e,"Sothing  went Rong while Updating data");  
         }
    }
    /**
     * Delete the cataegory
     * @param mixed $category
     * @return CategoryResources|mixed|\Illuminate\Http\JsonResponse
     */
    public function destroyService($category)
    {
        try {
                $category_old = Category::findOrFail($category->id);
                $category->delete();
                return new CategoryResources($category_old);
        } catch (Exception $e) {
            return $this->handleException($e,"Sothing  went Rong while Deleting data");  
         }
    }

    public function showService($category)
    {
        try{
        $category = Category::findOrFail($category->id);
        return new CategoryResources($category);
        } catch (Exception $e) {
            return $this->handleException($e,"Sothing   went Rong while Showing data");  
        }
    }
    //.................................................................
    //.................................................................
        /**
     * Handle the Exception
     * @param \Exception $e
     * @param string $message
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    protected function handleException(Exception $e, string $message)
    {
        // Log the error with additional context if needed
        Log::error($message, ['exception' => $e->getMessage(), 'request' => request()->all()]);

        return response()->json($e->getMessage(), 500);
    }
}