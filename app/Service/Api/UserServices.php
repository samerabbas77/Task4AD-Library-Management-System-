<?php

namespace App\Service\Api;

use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResources;
use Illuminate\Http\Resources\Json\JsonResource;

class UserServices
{
    /**
     * Summary of indexService
     * @return mixed|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Http\JsonResponse
     */
    public function indexService()
    {
       try{
        $data = User::latest()->paginate(5);
        return  UserResources::collection($data);
       }
       catch(\Exception $exception){
                return $this->handleException($exception,'Something Went Rong while fetching the users informationes');
            } 
        
    }

    //..................................................................
    //..................................................................
   
    public function storeService($input)
    {
        try{
            $input['password'] = Hash::make($input['password']);
           
            $user = User::create($input);
            //Assigne role to the user
            $user->assignRole($input['role']);

            return new UserResources($user);
        }catch(\Exception $exception){
            return $this->handleException($exception,'Something Went Rong while storing the user');
        }
    } 
    //..................................................................
    //..................................................................
    public function showService($user)
    {
       try {
        $user = User::findOrFail($user->id);
        return new UserResources($user);
       } catch (\Exception $exception) {
         return $this->handleException($exception,'Something Went Rong while fetching the user');
       } 
    }
    //..................................................................
    //..................................................................
    /**
     * update the user bt its id
     * @param mixed $data
     * @param mixed $user
     * @return mixed|UserResources|\Illuminate\Http\JsonResponse
     */
    public function updateService($data,$user)
    {
        try {
           $user = User::findOrFail($user->id);
            if($user->id == auth('api')->user()->id)
            {
                if($data['name']) $user->name = $data['name']; 
                if($data['email']) $user->email = $data['email']; 
                if($data['password'])
                {
                    $user->password = Hash::make($data['password']);
                }
                
                $user->save();
                if($data['role'])
                {
                    //delete old rules
                    DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
                    $user->assignRole($data['roles']);
                }
                
                return new UserResources($user);
            }else{
                return null;
            }
        } catch (\Exception $exception) {
            return $this->handleException($exception,'Something Went Rong while Updating the user');

        }
    }
        //..................................................................
       //..................................................................
       /**
        * destroy a user by its id
        * @param mixed $user
        * @return mixed|UserResources|\Illuminate\Http\JsonResponse
        */
       public function destroyService($user)
       {
        try {
            $data = User::findOrFail($user->id);
            if($data['id'] == auth('api')->user()->id)
            {
                $user->delete();
                return new UserResources($data);
            }else{
                return null;
            }

        } catch (\Exception $exception) {
            return $this->handleException($exception,'Something Went Rong while Updating the user');
        }
       }

    //............................End of CRUD...........................
    //.......................................................................
      


    //............................Hamdle Exception...........................
    //.......................................................................
    
    /**
     * Handle the Exception
     * @param \Exception $e
     * @param string $message
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    protected function handleException(\Exception $e, string $message)
    {
        // Log the error with additional context if needed
        Log::error($message, ['exception' => $e->getMessage(), 'request' => request()->all()]);

        return response()->json($e->getMessage(), 500);
    }
}
