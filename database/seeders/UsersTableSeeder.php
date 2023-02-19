<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        try {
            User::query()->firstOrCreate([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john_doe@gmail.com',
                'password' => bcrypt('secret'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'phone' => '+233248764986'
            ]);
        }catch (Exception $e){}

        if(User::query()->count() <= 5){
            User::factory(500)->create();
        }
    }
}
