<?php

namespace App\Services;

use App\Contracts\AnimalServiceInterface;
use Exception; 
use Illuminate\Support\Facades\DB;

class SQLAnimalService implements AnimalServiceInterface
{
    public function all(): array
    {
        $rows = DB::select('SELECT id, name, species, age FROM animals ORDER BY id ASC');
        
        return array_map(function ($row) {
            return (array) $row;
        }, $rows);
    }

    public function find(string $id): array
    {
        $row = DB::selectOne('SELECT id, name, species, age FROM animals WHERE id = ?', [$id]);
        
        if (!$row) {
            throw new Exception("No existe animal con ID: {$id}");
        }
        
        return (array) $row;
    }

    public function create(array $data): array
    {
        DB::insert(
            'INSERT INTO animals (name, species, age) VALUES (?, ?, ?)',
            [$data['name'], $data['species'], (int) $data['age']]
        );
        
        $result = DB::selectOne('SELECT last_insert_rowid() AS id');
        $newId = (string) $result->id;
        
        return $this->find($newId);
    }

    public function update(string $id, array $data): array
    {
        $this->find($id); 
        
        DB::update(
            'UPDATE animals SET name = ?, species = ?, age = ? WHERE id = ?',
            [$data['name'], $data['species'], (int) $data['age'], $id]
        );
        
        return $this->find($id);
    }

    public function delete(string $id): bool
    {
        $this->find($id);
        $affected = DB::delete('DELETE FROM animals WHERE id = ?', [$id]);
        return $affected > 0;
    }

    public function reset(): void
    {
        DB::statement('DELETE FROM animals');
        DB::statement("DELETE FROM sqlite_sequence WHERE name = 'animals'");
    }
}