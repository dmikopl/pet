<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class PetstoreService
{
    private string $baseUrl = 'https://petstore.swagger.io/v2';
    private array $validStatuses = ['available', 'pending', 'sold'];

    public function findPetsByStatus(array $statuses): array
    {
        $response = Http::timeout(10)->get("{$this->baseUrl}/pet/findByStatus", [
            'status' => implode(',', $statuses),
        ]);

        return $response->successful() ? $response->json() : [];
    }

    public function findPetById(int $petId): array
    {
        $response = Http::timeout(10)->get("{$this->baseUrl}/pet/{$petId}");
        if (!$response->successful()) {
            throw new \Exception("Failed to fetch pet: {$response->status()}");
        }
        return $response->json();
    }

    public function createPet(array $petData): array
    {
        $response = Http::timeout(10)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("{$this->baseUrl}/pet", $petData);

        if (!$response->successful()) {
            throw new \Exception("Failed to add pet: {$response->status()}");
        }
        return $response->json();
    }

    public function updatePet(int $petId, array $petData): array
    {
        $petData['id'] = $petId;
        $response = Http::timeout(10)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->put("{$this->baseUrl}/pet", $petData);

        if (!$response->successful()) {
            throw new \Exception("Failed to update pet: {$response->status()}");
        }
        return $response->json();
    }

    public function uploadPetImage(int $petId, $file, ?string $additionalMetadata): array
    {
        $response = Http::timeout(10)
            ->attach('file', file_get_contents($file->path()), $file->getClientOriginalName())
            ->post("{$this->baseUrl}/pet/{$petId}/uploadImage", [
                'additionalMetadata' => $additionalMetadata,
            ]);

        if (!$response->successful()) {
            throw new \Exception("Failed to upload image: {$response->status()}");
        }
        return $response->json();
    }

    public function deletePet(int $petId): void
    {
        $response = Http::timeout(10)
            ->withHeaders(['api_key' => env('PETSTORE_API_KEY', '')])
            ->delete("{$this->baseUrl}/pet/{$petId}");

        if (!$response->successful()) {
            throw new \Exception("Failed to delete pet: {$response->status()}");
        }
    }

    public function getValidStatuses(): array
    {
        return $this->validStatuses;
    }
}
