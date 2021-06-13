<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->name = $faker->name;
            $user->username = $faker->userName;
            $user->email = $faker->unique()->safeEmail;
            $user->password = Hash::make('admin');
            $user->level_id = 1;
            $user->is_active = 1;
            $user->save();
        }
    }
}
