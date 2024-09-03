<?php
namespace App\Service\Api;

use Exception;
use App\Models\Rating;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\RatingResource;

class RatingServices
{
    public function getRating()
    {
        try {
            $rating = Rating::where("user_id", auth('api')->user()->id)->get();
            return RatingResource::collection($rating);
        } catch (Exception $e) {
            return $this->handleException($e,"Sothing went Rong while Showing data");  
        }
    }

    //..................................................................
    //..................................................................
    /**
     * store validated data in database
     * @param mixed $validat
     * @return mixed|RatingResource|\Illuminate\Http\JsonResponse
     */
    public function storeService($validat)
    {
        try {
            //create the rating
            $rating = Rating::create($validat);

            return new RatingResource($rating);
        } catch (Exception $e) {
            return $this->handleException($e,"Sothing went Rong while Storing data");  
        }
    }

    //..................................................................
    //..................................................................
    /**
     * update a rating of specsfic book 
     * @param mixed $rating
     * @param mixed $validat
     * @return mixed|RatingResource|\Illuminate\Http\JsonResponse
     */
    public function updateService($rating,$validat)
    {
        try {
            //Check if the choosen rate is for the current user
            if($rating->user_id == auth("api")->user()->id) 
            {
                if($validat['rate'])
                    $rating->rate = $validat['rate'];
                $rating->save();

                 return new RatingResource($rating);
            } else {
                return null;
            }

        } catch (Exception $e) {
            return $this->handleException($e,"Sothing went Rong while Updaing data");  
        }
    }
//....................................................................
//....................................................................
    /**
     * * Delete a rating belongs to the current user
     * @param mixed $rating
     * @return mixed|RatingResource|\Illuminate\Http\JsonResponse|null
     */
    public function destroyService($rating)
    {
        try {
                //Check if the choosen rate is for the current user
                if($rating->user_id == auth("api")->user()->id) 
                {
                    $rating_old = Rating::findOrFail($rating->id);
                    $rating->delete();
    
                    return new RatingResource($rating_old);
                } else {
                    return null;
                }
        } catch (Exception $e) {
            return $this->handleException($e,"Sothing went Rong while Deleting data");  
        }
    }

    //.................................END OF CRUD...............................
    //............................................................................
    /**
     * Calculate the average rating of specific book
     * avr = total of rating / nimber of rate
     * @param mixed $book
     * @return float|int|mixed|\Illuminate\Http\JsonResponse
     */
    public function getAVRservice($book)
    {
        try {
            //get all of the ratings of this book
             $ratings = Rating::where('book_id', $book->id)->get();
            //Check tha array of items is empty(we can use is_null($rating) too)
            if($ratings->isEmpty())
            {
                return null;
            }else{
                //calculate number of rating
                $rate_num =count($ratings);

                //calculate the total Grand total of the rate 
                $rate_total = 0;
                foreach($ratings as $rating)
                {
                    $rate_total += $rating->rate;
                }
        
                return $rate_total/$rate_num;
            }
;
        } catch (Exception $e) {
            return $this->handleException($e,"Sothing went Rong while Calulating AVR");  
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