<?php

namespace App\Http\Requests\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ((auth('api')->user()) &&($this->user()->hasPermissionTo('user-edit') ));    
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      => 'string|max:50',
            'email'     => 'nullable|email|unique:users,email',
            'password'  => 'nullable|string|min:8|max:21',
            'role'      => 'nullable'
        ];
    }
}
