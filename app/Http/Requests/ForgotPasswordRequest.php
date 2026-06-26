<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', Rule::exists('users', 'username')]
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'اسم المستخدم مطلوب.',
            'username.exists'   => 'اسم المستخدم هذا غير موجود في النظام.',
        ];
    }
}
