<?php

namespace App\Services;

use App\Contracts\AnimalServiceInterface;
use Illuminate\Support\Facades\Storage;

class FileAnimalService implements AnimalServiceInterface
{
    private function getAnimals(): array
{
    if (!Storage::disk('local')->exists('animals.json')) {
        return [];
    }

    $json = Storage::disk('local')->get('animals.json');

    return json_decode($json, true) ?? [];
}
    
    private function saveAnimals(array $animals): void
    {
        $json = json_encode($animals, JSON_PRETTY_PRINT);
        Storage::disk('local')->put('animals.json', $json);
    }

    public function all(): array
    {
        $animals = $this->getAnimals();
        $animalsById = [];

        foreach ($animals as $animal) {
            $animalsById[$animal['id']] = $animal;
        }

        return $animalsById;
    }

    public function find(string $id): ?array
    {
        $animals = $this->getAnimals();

        foreach ($animals as $animal) {
            if ((string) $animal['id'] === $id) {
                return $animal;
            }
        }

        throw new \Exception("El animal con ID {$id} no fue encontrado.");
    }

    public function create(array $data): array
    {
        $animals = $this->getAnimals();

        $ids = array_column($animals, 'id');

        $newId = empty($ids) ? 1 : max($ids) + 1;

        $animal = ['id' => $newId] + $data;

        $animals[] = $animal;

        $this->saveAnimals($animals);

        return $animal;
    }   

    public function update(string $id, array $data): ?array
    {
        $animals = $this->getAnimals();

        foreach ($animals as $index => $animal) {
            if ((string) $animal['id'] === $id) {
                $updatedAnimal = ['id' => $animal['id']] + $data;

                $animals[$index] = $updatedAnimal;

                $this->saveAnimals($animals);

                return $updatedAnimal;
            }
        }

        throw new \Exception("El animal con ID {$id} no fue encontrado.");
    }

    public function delete(string $id): bool
    {
        $animals = $this->getAnimals();

        foreach ($animals as $index => $animal) {
            if ((string) $animal['id'] === $id) {
                unset($animals[$index]);

                $this->saveAnimals(array_values($animals));

                return true;
            }
        }

        throw new \Exception("El animal con ID {$id} no fue encontrado.");
    }
    
    public function reset(): void
    {
        $this->saveAnimals([]);
    }
}