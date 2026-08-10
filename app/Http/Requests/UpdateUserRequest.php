<?php

namespace App\Http\Requests;

use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'title' => 'nullable|string|max:20',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:20',
            'academic_title' => 'nullable|string|max:100',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('user', 'email')->ignore($userId)],
            'username' => ['required', 'string', 'max:255', Rule::unique('user', 'username')->ignore($userId)],
            'password' => 'nullable|string|min:6',
            'user_type' => ['required', Rule::in(UsersController::userTypes())],
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE',
        ];
    }
}
