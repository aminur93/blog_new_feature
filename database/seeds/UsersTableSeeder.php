<?php

use Illuminate\Database\Seeder;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_role')->insert(['name'=>'Admin','slug'=>'admin']);
    
        DB::table('users')->insert([
            'name' => 'khan ahmed',
            'user_role_id' => 1,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
