<?php

namespace App\Http\Requests;

use App\Http\Traits\Helpers;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    use Helpers;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if ($this->method() == 'POST') {
            return [
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', Password::min(8)],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp'],
                'is_admin' => ['nullable'],
                'role' => ['required']
            ];
        }
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'avatar' => 'avatar',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_admin' => $this->role == 'Admin' ? true : null,
        ]);
    }
}
