<?php
namespace App\Service\Api;

use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class RoleServices
{
    /**
     * get all the role
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function allRole()
    {
        try {
            $roles = Role::orderBy('id','DESC')->paginate(5);
            return $roles;
        } catch (Exception $e) {
            return $this->handleException($e,"Somthing went Rong while Fetching data");  
        }

    }
    //.................................................................
    //.................................................................
    /**
     * Summary of StoreService
     * @param mixed $validate
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function  StoreService($validate)
    {
        try {
            $permissionsID = array_map(
                function($value) { return (int)$value; },
                $validate->input('permission')
            );
        
            $role = Role::create(['name' => $validate->input('name')]);
            $role->syncPermissions($permissionsID);
            return $role;
        } catch (Exception $e) {
            return $this->handleException($e,"Somthing went Rong while Updating data");  
        }
    }
  
    //.................................................................
    //.................................................................
    /**
     * Summary of updateService
     * @param mixed $validate
     * @param mixed $id
     * @return mixed||\\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|null
     */
    public function  updateService($validate,$id)
    {
        try {
            $role = Role::find($id);
            $role->name = $validate->input('name');
            $role->save();
        
            $permissionsID = array_map(
                function($value) { return (int)$value; },
                $validate->input('permission')
            );
        
            $role->syncPermissions($permissionsID);
            return $role;
        
        } catch (Exception $e) {
            return $this->handleException($e,"Somthing went Rong while Storing data");  
        }
    } 
    //......................................................................
    //......................................................................
    public function getRoleById($id)
    {
        try {
          
            $role = Role::find($id);
            $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                ->where("role_has_permissions.role_id",$id)
                ->get();
            return $rolePermissions;   
        } catch (Exception $e) {
            return $this->handleException($e,"Somthing went Rong while fetching data");  
        }
    }
    //.................................................................
    //.................................................................

    public function deleteService($id)
    {
        try {
            $role = Role::find($id);
            DB::table("roles")->where('id',$id)->delete();
           return $role;
        } catch (Exception $e) {
            return $this->handleException($e,"Somthing went Rong while deleting data");  
        }
    }


    //.............................................................
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