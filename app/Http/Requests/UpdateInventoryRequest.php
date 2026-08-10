<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_name'    => 'sometimes|required|string|max:255',
            'category'     => 'sometimes|required|string|max:100',
            'brand'        => 'nullable|string|max:100',
            'color'        => 'nullable|string|max:50',
            'size'         => 'nullable|string|max:50',
            'type'         => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'current_stock'=> 'nullable|numeric|min:0',
            'quantity'     => 'nullable|numeric|min:0',
            'unit'         => 'nullable|string|max:50',
            'unit_cost'    => 'sometimes|numeric|min:0',
            'reorder_level'=> 'nullable|integer|min:0',
            'supplier_id'  => 'nullable|exists:supplier,supplier_id',
            'location'     => 'nullable|string|max:255',
            'receiver'     => 'nullable|string|max:255',
            'status'       => 'nullable|in:Active,Inactive,Discontinued',
            'received_notes' => 'nullable|string',
        ];
    }
}
