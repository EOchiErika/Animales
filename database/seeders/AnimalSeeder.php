<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        $animals = [
            ['name' => 'Milo', 'species' => 'Perro', 'age' => 5],
            ['name' => 'Luna', 'species' => 'Gato', 'age' => 2],
            ['name' => 'Paco', 'species' => 'Loro', 'age' => 12],
        ];

        foreach ($animals as $a) {
            DB::insert(
                'INSERT INTO animals (name, species, age) VALUES (?, ?, ?)',
                [$a['name'], $a['species'], $a['age']]
            );
        }
    }
}