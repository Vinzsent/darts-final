<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'title' => 'nullable|string|max:20',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:20',
            'academic_title' => 'nullable|string|max:100',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($userId)],
            'username' => ['required', 'string', 'max:255', Rule::unique('employees', 'username')->ignore($userId)],
            'department' => 'nullable|string|max:255',
            'current_password' => 'nullable|string|required_with:password',
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }
}
