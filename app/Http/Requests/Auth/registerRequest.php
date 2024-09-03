<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class registerRequest extends FormRequest
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
        return [
            'name'     => ['required','string','max:30'],
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','string','min:8','max:21']
        ];
    }

    /**
     * Custom messages for validation errors
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.required' => 'A password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must be at max 21 characters.',
            'name.reqiuired'=> 'Name is requiresd',
            'name.string'   => 'Name must be string',
            'name.max'      => 'Name must be Maxmume 30',
        ];
    }
}
