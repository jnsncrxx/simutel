<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
         \App\Models\User::create([
             'usertype' => 'guest',
              'email' => 'guest@gmail.com',
              'email_verified_at' => now(),
              'username' => 'guest',
              'password' => bcrypt('password'),
         ]);
         
         $admin = \App\Models\User::create([
             'usertype' => 'employee',
              'email' => 'admin@gmail.com',
              'email_verified_at' => now(),
              'username' => 'admin',
              'password' => bcrypt('password'),
         ]);
         
         $staff = \App\Models\User::create([
             'usertype' => 'employee',
              'email' => 'staff@gmail.com',
              'email_verified_at' => now(),
              'username' => 'staff',
              'password' => bcrypt('password'),
         ]);

        /*-----------------------------------------EMPLOYEES----------------------------------------- */
         \App\Models\Employees::create([
              'user_id' => $admin->id,
              'first_name' => 'Admin',
              'last_name' => 'Account',
              'contact' => '09123456789',
              'birthday' => Carbon::today()->toDateString(),
              'role' => 'Admin',
         ]);
         
         \App\Models\Employees::create([
              'user_id' => $staff->id,
              'first_name' => 'Staff',
              'last_name' => 'Account',
              'contact' => '09123456789',
              'birthday' => Carbon::today()->toDateString(),
              'role' => 'Staff',
         ]);
    }
}
