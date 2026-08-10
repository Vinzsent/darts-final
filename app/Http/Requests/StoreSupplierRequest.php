<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'email_address' => 'nullable|email|max:255',
            'fax_number' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'business_type' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:100',
            'tax_identification_number' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE,PENDING',
            'landline_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:255',
        ];
    }
}
