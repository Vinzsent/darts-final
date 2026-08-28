<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAirconRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_name'     => 'sometimes|required|string|max:255',
            'brand'         => 'nullable|string|max:100',
            'category'      => 'sometimes|required|string|max:100',
            'type'          => 'nullable|string|max:100',
            'capacity'      => 'nullable|string|max:50',
            'serial_number' => 'nullable|string|max:100',
            'color'         => 'nullable|string|max:50',
            'size'          => 'nullable|string|max:50',
            'description'   => 'nullable|string',
            'current_stock' => 'nullable|numeric|min:0',
            'unit'          => 'nullable|string|max:50',
            'unit_cost'     => 'sometimes|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'location'      => 'nullable|string|max:255',
            'receiver'      => 'nullable|string|max:255',
            'status'        => 'nullable|in:Active,Inactive,Discontinued',
            'received_notes' => 'nullable|string',
        ];
    }
}