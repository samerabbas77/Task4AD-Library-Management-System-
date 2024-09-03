<?php
  
namespace Database\Seeders;
  
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
  
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin= User::create([
                'name' => 'admin', 
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
                 'role'    =>'admin'
             ]
    );
    $role = Role::create(['name' => 'admin']);
         
    $permissions = Permission::all();
   
    $role->syncPermissions($permissions);

 
    $admin->assignRole($role);

//............................................................................
//............................................................................
 
    // Create the user
    $user = User::create([
        'name' => 'user', 
        'email' => 'user@gmail.com',
        'password' => bcrypt('12345678'),
        'role'     =>'user'
    ]);
    
    // Create the 'user' role
    $role = Role::create(['name' => 'user']);
    
    // Define the specific permissions you want to assign
    $permissions = [
        'book-list',
        'borrow-list',
        'rating-list',
        'user-edit',
        'user-delete'
    ];
    
    // Fetch only these specific permissions
    $permissions = Permission::whereIn('name', $permissions)->get();
    
    // Assign these permissions to the role
    $role->syncPermissions($permissions);

    
 
    
    // Assign the 'user' role to the user
    $user->assignRole($role);
    
    }
}
