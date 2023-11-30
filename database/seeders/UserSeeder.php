<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'newstein',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '0977999393',
            'address' => '491/West Ywama Insein, Yangon',
            'gender' => 'male',
            'registered_no' => 'M-000001',
            'identification_no' => '12/Ah Sa Na(N) 230 337'
        ]);

        $user->assignRole('member');
        $users  = User::factory(10)->create();
        foreach ($users as $value) {
            $value->assignRole('member');
        }
    }
}
