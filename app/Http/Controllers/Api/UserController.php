<?php
    
namespace App\Http\Controllers\Api;
    
use DB;
use Hash;
use App\Models\User;
use App\Trait\ResponseTrait;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Service\Api\UserServices;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
    
class UserController extends Controller
{
    use ResponseTrait;
    protected $userService;
    public function __construct(UserServices $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Fetching all the users
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = $this->userService->indexService();
        return $this->successResponse($data,'user have send successfully',200);        
    }
      
    /**
     * Store a newly user in storage.
     * @param \App\Http\Requests\User\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $input = $request->validated();
        
        $user = $this->userService->storeService($input);
        
        return $this->successResponse($user,'Store a user successfully',201);
    }
    
    /**
     * Show the user information
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
      $user = $this->userService->showService($user); 

      return $this->successResponse($user,'Store a user successfully',200);
    
    }
    
    /**
     * update user information
     * @param \App\Http\Requests\User\UpdateUserRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user =  $this->userService->updateService($data, $user);

        if($user ==null)
        {
            return response()->json("This is not your account");  
        }else
        {
            return $this->successResponse($user,'Store a user successfully',200);
        }
     

        
    }
    
    /**
     * emove the specified resource from storage.
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
         $data = $this->userService->destroyService($user);
        if($data == null)
        {
            return response()->json("This is not your account");
        }else{
            return $this->successResponse($data,'Delete a user Successfully',200);  
        }
        
    }
    
}
