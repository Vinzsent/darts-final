<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_name' => 'sometimes|required|string|max:255',
            'contact_person' => 'sometimes|nullable|string|max:255',
            'contact_number' => 'sometimes|nullable|string|max:50',
            'email_address' => 'sometimes|nullable|email|max:255',
            'fax_number' => 'sometimes|nullable|string|max:50',
            'website' => 'sometimes|nullable|url|max:255',
            'address' => 'sometimes|nullable|string|max:500',
            'city' => 'sometimes|nullable|string|max:100',
            'province' => 'sometimes|nullable|string|max:100',
            'zip_code' => 'sometimes|nullable|string|max:20',
            'country' => 'sometimes|nullable|string|max:100',
            'business_type' => 'sometimes|nullable|string|max:100',
            'category' => 'sometimes|nullable|string|max:255',
            'payment_terms' => 'sometimes|nullable|string|max:100',
            'tax_identification_number' => 'sometimes|nullable|string|max:100',
            'status' => 'sometimes|nullable|string|in:ACTIVE,INACTIVE,PENDING',
            'landline_number' => 'sometimes|nullable|string|max:100',
            'notes' => 'sometimes|nullable|string|max:255',
        ];
    }
}
