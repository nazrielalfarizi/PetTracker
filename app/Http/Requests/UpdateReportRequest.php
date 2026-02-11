<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // otorisasi kepemilikan dicek di controller
    }

    public function rules(): array
    {
        return [
            // TYPE (tidak bisa diubah, tapi tetap divalidasi)
            'type' => 'required|in:kehilangan,temuan',

            // DATA UMUM
            'title' => 'required|string|max:255',
            'description' => 'required|string',

            // IMAGE (opsional saat edit)
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // LOKASI
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',

            // KHUSUS KEHILANGAN
            'last_seen_location' => 'required_if:type,kehilangan|string',
            'pet_characteristics' => 'required_if:type,kehilangan|string',

            // KHUSUS TEMUAN
            'pet_condition' => 'required_if:type,temuan|string',
        ];
    }
}
