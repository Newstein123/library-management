<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\BookLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'newstein',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '0977999393',
            'address' => '491/West Ywama Insein, Yangon',
            'gender' => 'male',
            'registered_no' => 'M-000001',
            'identification_no' => '12/Ah Sa Na(N) 230 337'
        ]);

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            AuthorSeeder::class,
            CategorySeeder::class,
            PublisherSeeder::class,
            BookLocationSeeder::class,
            LanguageSeeder::class,
            BookSeeder::class,
        ]);
    }
}
