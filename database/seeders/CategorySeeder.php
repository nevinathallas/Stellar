<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Terrestrial Planet'], // Planet berbatu seperti Bumi, Mars
            ['name' => 'Gas Giant'], // Planet gas raksasa seperti Jupiter, Saturnus
            ['name' => 'Ice Giant'], // Planet es raksasa seperti Uranus, Neptunus
            ['name' => 'Dwarf Planet'], // Planet kerdil seperti Pluto
            ['name' => 'Rocky Planet'], // Planet berbatu
            ['name' => 'Ringed Planet'], // Planet bercincin
            ['name' => 'Inner Solar System'], // Sistem tata surya bagian dalam
            ['name' => 'Outer Solar System'], // Sistem tata surya bagian luar
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
