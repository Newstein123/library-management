<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '0977999393',
            'address' => '491/West Ywama Insein, Yangon',
            'gender' => 'male',
            'registered_no' => 'A-000001',
            'identification_no' => '12/Ah Sa Na(N) 230 337'
        ]);
        $admin->assignRole('admin');
    }
}
