<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        //(users table)
            'name'           => ['required', 'string', 'max:255'],
            'username'       => ['required', 'string', 'unique:users,username'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'password'       => ['required', 'string', 'min:8'],
            'phone_number'   => ['nullable', 'string', 'unique:users,phone_number'],
            'status'         => ['nullable', 'in:active,inactive'],

         //(teachers table)
            'subject_id'     => ['required', 'integer', 'exists:subjects,id'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio'            => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'اسم المعلم مطلوب.',
            'username.required'   => 'اسم المستخدم مطلوب.',
            'username.unique'     => 'اسم المستخدم محجوز مسبقاً.',
            'email.required'      => 'البريد الإلكتروني مطلوب.',
            'email.email'         => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'        => 'البريد الإلكتروني مسجل مسبقاً.',
            'password.required'   => 'كلمة المرور مطلوبة.',
            'password.min'        => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
            'phone_number.unique' => 'رقم الهاتف مسجل مسبقاً.',
            'status.in'           => 'حالة الحساب غير صالحة.',
            'subject_id.required' => 'تحديد المادة الدراسية مطلوب.',
            'subject_id.exists'   => 'المادة الدراسية المحددة غير موجودة في النظام.',
        ];
    }
}
