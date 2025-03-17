<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Services\PetstoreService;

class UpdatePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $service = app(PetstoreService::class);
        return [
            'name' => 'required|string|max:255',
            'status' => ['required', Rule::in($service->getValidStatuses())],
            'photo_urls' => 'nullable|string',
            'tags' => 'nullable|string',
        ];
    }
}
