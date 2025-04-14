<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|string|max:225',
            'description' => 'nullable|string|max:225',
            'media.*' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mkv|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ];
    }
}
