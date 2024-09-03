<?php

namespace App\Service\Api;

use Exception;
use Carbon\Carbon;
use LogicException;
use App\Models\Borrow;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\BorrowResources;

class BorrowServices
{
    /**
     * get all of the Borrows of the current user
     * @return BorrowResources|mixed|\Illuminate\Http\JsonResponse
     */
    public function getBorrowService()
    {
        try {
            //Look for the list of the borrow book that the user (who call this function) is late to return
            $borrowRecords = Borrow::where('user_id', auth('api')->id())->get();
                //for evry borrow have due date after today date take an action
                foreach ($borrowRecords as $record) {
                if (Carbon::now()->greaterThan($record->due_date) && is_null($record->returned_at)) {
                    // the action is to add more 14 days(for example)
                    $record->due_date = Carbon::parse($record->due_date)->addDays(14);
                    $record->save();
                }
            }


            $borrows = Borrow::with("book")->where('user_id',auth('api')->user()->id)->with('book')->get();
            
            return new BorrowResources($borrows);
        } catch (Exception $e) {
           return $this->handleException($e,'somthing went rong with fetching the borrow');
        }
    }
   //.............................................................
    //.............................................................
    /**
     * store a borrow after make sure the book available 
     * @param mixed $data
     * @throws \LogicException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function storeBorrow($data)
    {
        try {
            //Chech if the book excists( returned_at is not null)
             $borrow_check = Borrow::where('book_id',$data['book_id'])
             ->whereNull('returned_at')
             ->exists();
       
            if($borrow_check)
            {
                return null;
            } else {
                $borrow = Borrow::create([
                    'book_id'      => $data['book_id'],
                    'borrowed_at'  => $data['borrowed_at'],
                    'due_date'     => $data['due_date'],    
                 ]);
                return new BorrowResources($borrow);
            }

        } catch (Exception $e) {
            return $this->handleException($e,'somthing went rong with storing the borrow');

        }
    }
     //.............................................................
    //.............................................................
    /**
     * update Borrow by its id
     * not allowed to change booraw date(use renew function)
     * 
     * @param mixed $borrow
     * @param mixed $data
     * @return BorrowResources|mixed|\Illuminate\Http\JsonResponse|null
     */
    public function updateBorrow($borrow,$data)
    {
        try {
              
            if($data['returned_at'] )
            {
                $borrowed_date = Carbon::parse($borrow->borrowed_at);
                $returned_date = Carbon::parse($data['returned_at']);

               //check if the returned date is after the burred date
                if ($borrowed_date->lt($returned_date)) 
                {
                    //if the book title exsist update it (maby the admin make mistake when entring the book titlte)
                    if($data['book_title'] && $data['book_id']){ $borrow->book_id = $data['book_id'];}
                    $borrow->returned_at = $data['returned_at'];
                    $borrow->save();
                    return  new BorrowResources($borrow);
                }else
                {
                    return null;
                    
                }


            }
  
        } catch (Exception $e) {
            return $this->handleException($e,'somthing went rong with updating the borrow');
        }
    }

    //.............................................................
    //.............................................................


    public function destroyBorrow($borrow)
    {
        try {
            $find_borrow = Borrow::findOrFail($borrow->id);
       
            if($borrow->returned_at)
            {
               $borrow->delete();
                return new BorrowResources($find_borrow);

            }else{
                return null;
            }


        } catch (Exception $e) {
            return $this->handleException($e,'somthing went rong with Deleting the borrow');
 
        }
    }

    //................................END OF CRUD.............................
    //........................................................................
    /**
     *  renewBorrow
     * i auto calculate the due date in the request class
     * @param mixed $borrow
     * @param mixed $data
     * @return BorrowResources|mixed|\Illuminate\Http\JsonResponse
     */
    public function renewBorrow($borrow,$data)
    {
        try {
            //check if the new borrow is after the old one
            $old = Carbon::parse($borrow->borrowed_at);
            $new = Carbon::parse($data['borrowed_at']);
             //Renew the Borrow
             if($old->lt( $new) )
             {
                //give retirn date null value
                $borrow->returned_at =null;
                //save the info
                $borrow->borrowed_at = $data['borrowed_at'];
                $borrow->due_date = $data['due_date'];
                $borrow->save();

                return new BorrowResources($borrow);
             }else{
                return null;
             }
        } catch (Exception $e) {
            return $this->handleException($e,'somthing went rong with updating the borrow');
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