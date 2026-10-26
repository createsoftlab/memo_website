<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $targetUser = $this->user ?? $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($targetUser)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($targetUser)],
            'phone' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'in:super_admin,admin,employee,memory_user,general_user'],
            'status' => ['required', 'in:active,suspended,banned'],
            'permissions' => ['nullable', 'array'],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'username.unique' => 'This username is already taken.',
            'email.unique' => 'This email address is already in use.',
            'role.in' => 'Selected role is invalid.',
            'status.in' => 'Selected status is invalid.',
        ];
    }
}
