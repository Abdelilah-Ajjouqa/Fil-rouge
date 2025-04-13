<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => ['required', 'string', 'max:225'],
            'post_id' => ['required', 'exists:posts,id']
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Comment content is required',
            'content.max' => 'Comment content cannot exceed 225 characters',
            'post_id.required' => 'Post ID is required',
            'post_id.exists' => 'Selected post does not exist'
        ];
    }
}
