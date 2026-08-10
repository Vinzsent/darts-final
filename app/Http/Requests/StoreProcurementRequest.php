<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id'     => 'required|exists:supplier,supplier_id',
            'invoice_no'      => 'required|string|max:255',
            'date_received'   => 'required|date',
            'item_description' => 'required|string|max:500',
            'brand'           => 'nullable|string|max:255',
            'type'            => 'nullable|string|max:255',
            'color'           => 'nullable|string|max:100',
            'quantity'        => 'required|integer|min:1',
            'unit'            => 'required|string|max:50',
            'unit_price'      => 'required|numeric|min:0',
            'amount'          => 'nullable|numeric|min:0',
            'sales_type'      => 'nullable|string|max:100',
            'category'        => 'nullable|string|max:100',
            'status'          => 'nullable|string|max:50',
        ];
    }
}
