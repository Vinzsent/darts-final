<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_return'         => 'nullable|date',
            'temporary_transfer'  => 'nullable|in:Yes,No',
            'permanent_transfer'  => 'nullable|in:Yes,No',
            'reason_for_transfer' => 'required|string|max:1000',
            'category'            => 'required|string|max:255',
            'item_name'           => 'required|string|max:255',
            'request_description' => 'nullable|string|max:1000',
            'brand'               => 'nullable|string|max:255',
            'color'               => 'nullable|string|max:100',
            'type'                => 'nullable|string|max:100',
            'quantity_requested'  => 'required|integer|min:1',
            'request_type'        => 'nullable|string|max:100',
            'tagging'             => 'nullable|string|max:100',
            'department_unit'     => 'required|string|max:255',
            'qrcode'              => 'nullable|string|max:255',
        ];
    }
}
