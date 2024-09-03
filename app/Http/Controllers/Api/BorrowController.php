<?php

namespace App\Http\Controllers\Api;

use App\Models\Borrow;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;
use App\Service\Api\BorrowServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\Borrow\RenwRequest;
use App\Http\Requests\Borrow\StoreRequest;
use App\Http\Requests\Borrow\UpdateRequest;

class BorrowController extends Controller
{
    use ResponseTrait;

    protected $borrowService;

    public function __construct(BorrowServices $borrowService)
    {
        $this->borrowService = $borrowService;
    }
    /**
     * Summary of index
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $borrows = $this->borrowService->getBorrowService();
        
        return $this->successResponse($borrows,"Get All user borrows successfully");
    }
//.................................................................
//.................................................................
    /**
     * store a borrow 
     * @param \App\Http\Requests\Borrow\StoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $request->validated();
        
        $data = $request->only(['book_title','borrowed_at', 'book_id', 'due_date']);
    
        $borrow =  $this->borrowService->storeBorrow($data);
        if( !empty($borrow))
        { 
            return $this->successResponse($borrow,'Store Borrow Successfully');
        }else{
                return response()->json("The book is already borrowed.");
                }           
}

    /**
     * Display the specified resource.
     */
    public function show(Borrow $borrow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\Borrow\UpdateRequest $request
     * @param \App\Models\Borrow $borrow
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Borrow $borrow)
    {
        
        $request->validated();

        $request_data = $request->only(['book_title','borrowed_at', 'book_id', 'due_date','returned_at']);
     
        $newBorrow= $this->borrowService->updateBorrow($borrow, $request_data);
   
        if(!empty($newBorrow))
        {
            return $this->successResponse($newBorrow,'Update Borrow Successfully');
        }else{
            return response()->json("The Returned date Should be after borrowed day ");
        }
            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow $borrow)
    {
       
       $borrow_deleted = $this->borrowService->destroyBorrow($borrow);
      
       if(!empty($borrow_deleted))
        {
            return $this->successResponse($borrow_deleted,'Deleted Borrow Successfully');
        }else{
            return response()->json("You cant delete Unreturned borrow(the  Book still not returned) ");
        }
    }


    //......................................................
    //.........................................................

    /**
     * renew the borrow date 
     * @param \App\Http\Requests\Borrow\RenwRequest $request
     * @param \App\Models\Borrow $borrow
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function renew(RenwRequest $request,Borrow $borrow)
    {
        $request->validated();

        $request_data = $request->only(["borrowed_at","due_date"]);

        $new_borrow= $this->borrowService->renewBorrow($borrow, $request_data);

        if(!empty($new_borrow))
        {
            return $this->successResponse($new_borrow,'Renew Borrow Successfully');
            
        }else{
            return response()->json("The new Borrow date Should be after the old one ");
        }
    }
   
}
