<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=0;$i<1;$i++){
            $input = ['admin','editor','author'];
            $rand_keys = array_rand($input);
            $data[$i] = [
            //    'name' => $faker->name,
            //    'email' => $faker->unique()->safeEmail,
               'name' => 'test',
               'email' => 'test@test.com',
               'email_verified_at' => now(),
               'password' => bcrypt('test'),
               'remember_token' => Str::random(10),
               'role' => $input[$rand_keys],
            ];
        }
        
        DB::table('users')->insert($data);
    }
}
