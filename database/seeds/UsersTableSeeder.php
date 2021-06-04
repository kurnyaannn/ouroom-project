<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_user')->insert([
            'username' => 'creator',
            'email' => 'creator@ouroom.com',
            'password' => Hash::make('creatoratm'),
            'full_name' => 'Super Admin',
            'account_type' => 'Creator'
        ]);
    }
}
