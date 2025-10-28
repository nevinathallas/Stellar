<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Planet data with categories
        $units = [
            [
                'code' => 'PLN-001',
                'name' => 'Mercury',
                'price_per_day' => 500000,
                'status' => 'available',
                'categories' => [1, 5, 7] // Terrestrial, Rocky, Inner Solar System
            ],
            [
                'code' => 'PLN-002',
                'name' => 'Venus',
                'price_per_day' => 750000,
                'status' => 'available',
                'categories' => [1, 5, 7] // Terrestrial, Rocky, Inner Solar System
            ],
            [
                'code' => 'PLN-003',
                'name' => 'Mars',
                'price_per_day' => 1000000,
                'status' => 'available',
                'categories' => [1, 5, 7] // Terrestrial, Rocky, Inner Solar System
            ],
            [
                'code' => 'PLN-004',
                'name' => 'Jupiter',
                'price_per_day' => 2500000,
                'status' => 'available',
                'categories' => [2, 8] // Gas Giant, Outer Solar System
            ],
            [
                'code' => 'PLN-005',
                'name' => 'Saturn',
                'price_per_day' => 3000000,
                'status' => 'available',
                'categories' => [2, 6, 8] // Gas Giant, Ringed Planet, Outer Solar System
            ],
            [
                'code' => 'PLN-006',
                'name' => 'Uranus',
                'price_per_day' => 2000000,
                'status' => 'available',
                'categories' => [3, 6, 8] // Ice Giant, Ringed Planet, Outer Solar System
            ],
            [
                'code' => 'PLN-007',
                'name' => 'Neptune',
                'price_per_day' => 2200000,
                'status' => 'available',
                'categories' => [3, 8] // Ice Giant, Outer Solar System
            ],
            [
                'code' => 'PLN-008',
                'name' => 'Pluto',
                'price_per_day' => 800000,
                'status' => 'available',
                'categories' => [4, 8] // Dwarf Planet, Outer Solar System
            ],
            [
                'code' => 'PLN-009',
                'name' => 'Mars', // Nama bisa sama, tapi kode berbeda
                'price_per_day' => 1200000,
                'status' => 'available',
                'categories' => [1, 5, 7] // Terrestrial, Rocky, Inner Solar System
            ],
            [
                'code' => 'PLN-010',
                'name' => 'Jupiter', // Nama bisa sama, tapi kode berbeda
                'price_per_day' => 2800000,
                'status' => 'available',
                'categories' => [2, 8] // Gas Giant, Outer Solar System
            ],
        ];

        foreach ($units as $unitData) {
            $categories = $unitData['categories'];
            unset($unitData['categories']);
            
            $unit = Unit::create($unitData);
            $unit->categories()->attach($categories);
        }
    }
}
