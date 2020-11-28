<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        /*
        DB::table('users')->insert([
            'name'      =>   'primeiro Usuário',
            'email'     =>   'email@email.com',
            'password'  =>   bcrypt('senha')
        ]);
        */

        factory(\App\User::class,10)->create();
    }
}
