<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        $route = $this->route()->getName();

        if ($route === 'auth.register') {
            $rules = [
                'first_name' => 'required|string|max:225',
                'last_name' => 'required|string|max:225',
                'username' => 'required|string|max:225|unique:users',
                'email' => 'required|email|string|max:225|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ];
        } elseif ($route === 'auth.login') {
            $rules = [
                'email' => 'required|string|email|max:225',
                'password' => 'required|string|min:8',
            ];
        }

        return $rules;
    }
}
