<?php

namespace Database\Seeders;

use App\Models\BookLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aisleNames = [
            'A1', 'A2', 'A3', 'A4', 'A5',
            'B1', 'B2', 'B3', 'B4', 'B5',
            'C1', 'C2', 'C3', 'C4', 'C5',
            'D1', 'D2', 'D3', 'D4', 'D5',
            'E1', 'E2', 'E3', 'E4', 'E5',
            'F1', 'F2', 'F3', 'F4', 'F5',
            'G1', 'G2', 'G3', 'G4', 'G5',
            'H1', 'H2', 'H3', 'H4', 'H5',
            'I1', 'I2', 'I3', 'I4', 'I5',
            'J1', 'J2', 'J3', 'J4', 'J5',
        ];
        foreach ($aisleNames as $value) {
           BookLocation::create([
            'aisle' => $value,
           ]);
        }        
    }
}
