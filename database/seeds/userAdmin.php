<?php

use Illuminate\Database\Seeder;
use App\User;

class userAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'user_name' => 'superadmin',
            'user_password' => Hash::make('superadmin'),
            'user_active' => '1',
            'user_full_name' => 'superadmin',
            'user_created_at' => now()->toDateTimeString(),
            'user_created_by' => '1'
        ]);
    }
}
