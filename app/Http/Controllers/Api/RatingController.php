<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Rating;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;
use App\Service\Api\RatingServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\RatingStoreRequest;
use App\Http\Requests\Rating\RatingUpdateRequest;

class RatingController extends Controller
{
    use ResponseTrait;
    protected $ratingService;
    public function __construct(RatingServices $ratingService)
    {
        $this->ratingService = $ratingService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ratings = $this->ratingService->getRating();
        return $this->successResponse($ratings,"Shoing Rating Successfully");
    }

    //...................................................................
    //...................................................................

    /**
     * store a rate 
     * i genrate 'book_id' in the request and send it with it to here
     * @param \App\Http\Requests\Rating\RatingStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RatingStoreRequest $request)
    {
        $request->validated();

        $validat = $request->only(['rate', 'book_id']);

        $rating = $this->ratingService->storeService($validat);

        return $this->successResponse($rating,"Storing Rating Successfully");
    }
    //.......................................................................
    //.......................................................................

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        
    }
//..........................................................................
//..........................................................................
    /**
     * update a rate belongs to current user
     * @param \App\Http\Requests\Rating\RatingUpdateRequest $request
     * @param \App\Models\Rating $rating
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update( RatingUpdateRequest $request, Rating $rating)
    {
        $request->validated();

        $validat = $request->only(['rate']);

        $rating = $this->ratingService->updateService($rating,$validat);
        if(!empty($rating)) 
        {
             return $this->successResponse($rating,"Updaing Rating Successfully");
        }else{
            return response()->json("Not your Rating",404);
        }  
    }
//......................................................................
//......................................................................
    /**
     * Delete a rating belongs to the current user
     * @param \App\Models\Rating $rating
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Rating $rating)
    {
        $old = $this->ratingService->destroyService($rating);
        if(!empty($old))
        {
            return $this->successResponse($old,"Deleting Rating Successfully");
        }else{
            return response()->json("Not your Rating",404);
        }
    }
    //.......................................................................
    //.......................................................................

    public function getAVRrating(Book $book)
    {
       
       $avr = $this->ratingService->getAVRservice($book);

       if($avr == null)
       {
        return response()->json("No Rating for this book",404);
       }else{
        return response()->json([
            "AVR"  => $avr,
            'message' =>"Calculate AVR Successfully",
            'code'   =>200
           ]);
       } 
    }
}
