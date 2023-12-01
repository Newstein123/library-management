<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $editor = User::create([
            'name' => 'editor',
            'email' => 'editor@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '0977999393',
            'address' => '491/West Ywama Insein, Yangon',
            'gender' => 'male',
            'registered_no' => 'A-000002',
            'identification_no' => '12/Ah Sa Na(N) 230 337'
        ]);
        $editor->assignRole('editor');
    }
}
