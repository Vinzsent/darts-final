<?php

namespace App\Http\Requests;

use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:20',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:20',
            'academic_title' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255|unique:employees,email',
            'username' => 'required|string|max:255|unique:employees,username',
            'password' => 'required|string|min:6',
            'user_type' => ['required', Rule::in(UsersController::userTypes())],
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE',
        ];
    }
}
