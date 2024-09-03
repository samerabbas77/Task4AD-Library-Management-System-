<?php

namespace App\Http\Controllers\api;

use Illuminate\View\View;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;
use App\Service\Api\RoleServices;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Role\StoreRequest;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Role\UpdateRequest;

    
class RoleController extends Controller
{
    use ResponseTrait;
    protected $roleService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct ( RoleServices $roleService)
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);

        $this->roleService = $roleService;
    }

    //.............................................................
    //.............................................................
    /**
     * Get all Roles
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = $this->roleService->allRole();
  
        return $this->successResponse($roles,'Fetch all Roles sussefully');
    }
    //.............................................................
    //.............................................................
    /**
     * store a new rule
     * @param \App\Http\Requests\Role\StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $validate = $request->validated();
        $role = $this->roleService->StoreService($validate);
  
        return $this->successResponse($role,'success','Role Stored successfully');
    }

    //.............................................................
    //.............................................................                     
    /**
     * Summary of show
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $role = $this->roleService->getRoleById($id);
    
        return$this->successResponse($role,'Get Roll successfully');
    }

    //.............................................................
    //.............................................................
    
    /**
     * update an role
     * @param \App\Http\Requests\Role\UpdateRequest $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( UpdateRequest $request, $id)
    {
         $validate = $this->validated();
         $role =  $this->roleService->updateService($validate,$id);

        return $this->successResponse($role,'success','Role updated successfully');
    }

    //.............................................................
    //.............................................................
    /**
     * delete a role
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role =  $this->roleService->deleteService($id);
        
        return $this->successResponse($role,'Role deleted successfully');
    }
}

