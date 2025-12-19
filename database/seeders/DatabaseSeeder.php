<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create user types
        DB::table('usertype')->insert([
            ['id' => 1, 'usertype_name' => 'Admin'],
            ['id' => 2, 'usertype_name' => 'User'],
        ]);

        // Create admin user
        $admin = User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'usertype_id' => 1, // Admin role
            'email_verified_at' => now(),
        ]);

        // Create admin profile
        \App\Models\Profile::create([
            'user_id' => $admin->id,
            'fname' => 'Admin',
            'mname' => '',
            'lname' => 'User',
            'contactnum' => '0000000000',
        ]);
    }
}
