<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=>'Arif islam',
            'email'=>'admin@gmail.com',
            'password'=>Hash::make('123456'),
            'phone'=>'01723093965',
            'role'=>'admin',
            'image'=>'123456.png'
        ]);
    }
}
