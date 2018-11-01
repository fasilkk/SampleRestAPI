<?php

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert([

            1 => [

                'username' => 'moliio_user',
                'password' => Hash::make('123123'),
                'email'    => 'fasilkk.me@gmail.com',
                'fname'    => 'first',
                'lname'    => 'lirst',
                'address'  => 'molio #123, addrress',

            ],

        ]);
    }
}
