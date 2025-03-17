<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\PetstoreService;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Requests\UpdatePetWithFormRequest;
use App\Http\Requests\UploadPetImageRequest;

class PetController extends Controller
{
    protected PetstoreService $petstoreService;

    public function __construct(PetstoreService $petstoreService)
    {
        $this->petstoreService = $petstoreService;
    }

    public function form()
    {
        return view('pets.form');
    }

    public function index(Request $request)
    {
        try {
            $statuses = $request->input('status', $this->petstoreService->getValidStatuses());
            $statuses = is_array($statuses) ? $statuses : explode(',', $statuses);

            foreach ($statuses as $status) {
                if (!in_array($status, $this->petstoreService->getValidStatuses())) {
                    return view('pets.index', [
                        'error' => "Invalid status value: {$status}",
                        'pets' => new LengthAwarePaginator([], 0, 30),
                        'selectedStatuses' => $statuses,
                    ]);
                }
            }

            $petsData = $this->petstoreService->findPetsByStatus($statuses);

            $perPage = 30;
            $currentPage = $request->input('page', 1);
            $petsCollection = collect($petsData);
            $total = $petsCollection->count();
            $pets = new LengthAwarePaginator(
                $petsCollection->forPage($currentPage, $perPage),
                $total,
                $perPage,
                $currentPage,
                ['path' => route('pets.index')]
            );

            return view('pets.index', [
                'pets' => $pets,
                'selectedStatuses' => $statuses,
            ]);
        } catch (\Exception $e) {
            return view('pets.index', [
                'error' => "Service unavailable: {$e->getMessage()}",
                'pets' => new LengthAwarePaginator([], 0, 30),
                'selectedStatuses' => $request->input('status', $this->petstoreService->getValidStatuses()),
            ]);
        }
    }

    public function show(int $petId)
    {
        try {
            $pet = $this->petstoreService->findPetById($petId);
            return view('pets.show', ['pet' => $pet]);
        } catch (\Exception $e) {
            return $this->indexWithError("Failed to fetch pet: {$e->getMessage()}");
        }
    }

    public function store(StorePetRequest $request)
    {
        try {
            $petData = [
                'id' => 0,
                'name' => $request->name,
                'status' => $request->status,
                'photoUrls' => $request->photo_urls ? explode(',', $request->photo_urls) : [],
                'tags' => $request->tags ? array_map(fn($tag) => ['id' => 0, 'name' => trim($tag)], explode(',', $request->tags)) : [],
                'category' => ['id' => 0, 'name' => 'default'],
            ];

            $response = $this->petstoreService->createPet($petData);
            return redirect()->route('pets.show', ['petId' => $response['id']])
                ->with('success', "Pet {$response['id']} added successfully");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => "Failed to add pet: {$e->getMessage()}"]);
        }
    }

    public function edit(int $petId)
    {
        try {
            $pet = $this->petstoreService->findPetById($petId);
            return view('pets.edit', ['pet' => $pet]);
        } catch (\Exception $e) {
            return $this->indexWithError("Failed to fetch pet: {$e->getMessage()}");
        }
    }

    public function update(UpdatePetRequest $request, int $petId)
    {
        try {
            $petData = [
                'name' => $request->name,
                'status' => $request->status,
                'photoUrls' => $request->photo_urls ? explode(',', $request->photo_urls) : [],
                'tags' => $request->tags ? array_map(fn($tag) => ['id' => 0, 'name' => trim($tag)], explode(',', $request->tags)) : [],
                'category' => ['id' => 0, 'name' => 'default'],
            ];

            $this->petstoreService->updatePet($petId, $petData);
            return redirect()->route('pets.index')->with('success', 'Pet updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => "Failed to update pet: {$e->getMessage()}"]);
        }
    }

    public function uploadImage(UploadPetImageRequest $request, int $petId)
    {
        try {
            $this->petstoreService->uploadPetImage($petId, $request->file('file'), $request->additionalMetadata);
            return redirect()->route('pets.show', $petId)->with('success', 'Image uploaded successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => "Failed to upload image: {$e->getMessage()}"]);
        }
    }

    public function destroy(int $petId)
    {
        try {
            $this->petstoreService->deletePet($petId);
            return redirect()->route('pets.index')->with('success', 'Pet deleted successfully');
        } catch (\Exception $e) {
            return $this->indexWithError("Failed to delete pet: {$e->getMessage()}");
        }
    }

    private function indexWithError(string $errorMessage)
    {
        try {
            $statuses = request('status', $this->petstoreService->getValidStatuses());
            $statuses = is_array($statuses) ? $statuses : explode(',', $statuses);

            $petsData = $this->petstoreService->findPetsByStatus($statuses);
            $perPage = 30;
            $currentPage = request()->input('page', 1);
            $petsCollection = collect($petsData);
            $total = $petsCollection->count();
            $pets = new LengthAwarePaginator(
                $petsCollection->forPage($currentPage, $perPage),
                $total,
                $perPage,
                $currentPage,
                ['path' => route('pets.index')]
            );

            return view('pets.index', [
                'pets' => $pets,
                'selectedStatuses' => $statuses,
                'error' => $errorMessage,
            ]);
        } catch (\Exception $e) {
            return view('pets.index', [
                'pets' => new LengthAwarePaginator([], 0, 30),
                'selectedStatuses' => $this->petstoreService->getValidStatuses(),
                'error' => $errorMessage . " (Additional error: {$e->getMessage()})",
            ]);
        }
    }
}
