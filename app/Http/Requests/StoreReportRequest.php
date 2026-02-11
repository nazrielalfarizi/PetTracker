<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
        {
            return [
                'type' => 'required|in:kehilangan,temuan',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image',
                'latitude' => 'required',
                'longitude' => 'required',
                'address' => 'required',

                // Conditional validation
                'last_seen_location' => 'required_if:type,kehilangan',
                'pet_characteristics' => 'required_if:type,kehilangan',
                'pet_condition' => 'required_if:type,temuan',
            ];
        }
}
