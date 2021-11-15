<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Sigi Kemahduta',
                'username' => 'sigi',
                'password' => Hash::make('password'),
                'email' => 'sigi@mail.com',
                'birth_date' => Carbon::parse('2000-01-01'),
                'address' => 'Indonesia',
                'active' => 1
            ]
        ]);
    }
}
