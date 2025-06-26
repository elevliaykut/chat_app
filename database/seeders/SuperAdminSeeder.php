<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'                  => 'admin',
            'surname'               => 'admin',
            'username'              => 'admin',
            'email'                 => 'admin@gmail.co',
            'password'              => Hash::make('admin.2025!!!'),
            'phone'                 => '5379105934',
            'type'                  => 2,
            'status'                => 1,
            'created_at'            => Carbon::now(),
            'updated_at'            => Carbon::now()
        ]);
    }
}
