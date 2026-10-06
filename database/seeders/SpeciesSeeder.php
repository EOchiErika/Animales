<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpeciesSeeder extends Seeder
{
    public function run(): void
    {
        $species = [
            'Perro', 'Gato', 'Loro', 'Canario', 'Tortuga',
            'Iguana', 'Hámster', 'Pez', 'Conejo', 'Caballo'
        ];

        foreach ($species as $name) {
            DB::insert('INSERT INTO species (name) VALUES (?)', [$name]);
        }
    }
}