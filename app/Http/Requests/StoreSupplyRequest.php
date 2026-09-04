<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'department_unit'      => 'required|string|max:255',
            'purpose'              => 'required|string|max:500',
            'category'             => 'required|string|max:255',
            'item_name'            => 'required|string|max:255',
            'quantity_requested'   => 'required|integer|min:1',
            'unit'                 => 'required|string|max:50',
            'request_description'  => 'nullable|string|max:1000',
            'brand'                => 'nullable|string|max:255',
            'color'                => 'nullable|string|max:100',
            'size'                 => 'nullable|string|max:100',
            'type'                 => 'nullable|string|max:100',
            'unit_cost'            => 'nullable|numeric|min:0',
            'total_cost'           => 'nullable|numeric|min:0',
            'sales_type'           => 'nullable|string|max:100',
            'request_type'         => 'nullable|string|max:100',
            'semester'             => 'nullable|string|max:50',
            'school_year'          => 'nullable|string|max:50',
            'remarks'              => 'nullable|string|max:5000',
            'qrcode'               => 'nullable|string|max:255',
        ];

        // Only enforce future date on create — allow editing existing past dates
        $rules['date_needed'] = $this->isMethod('post')
            ? 'required|date|after:today'
            : 'required|date';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'date_needed.after' => 'The date needed must be a future date.',
            'quantity_requested.min' => 'Quantity must be at least 1.',
        ];
    }
}
