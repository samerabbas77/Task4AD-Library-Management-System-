<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the CategorySeeder
        $this->call(CategorySeeder::class);
        
        //create the permation
        $this->call(PermissionTableSeeder::class);

        // User::factory(10)->create();
        $this->call(CreateAdminUserSeeder::class);


  

    }
}
