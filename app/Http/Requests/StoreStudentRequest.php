<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //(User Table)
            'name'           => ['required', 'string', 'max:255'],
            'username'       => ['required', 'string', 'unique:users,username'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'password'       => ['required', 'string', 'min:8'],
            'phone_number'   => ['nullable', 'string', 'unique:users,phone_number'],
            'status'         => ['nullable', 'in:active,inactive'],

            //(Student Table)
            'grade_id'       => ['required', 'integer', 'exists:grades,id'],
            'parent_phone'   => ['nullable', 'string'],
            'gender'         => ['nullable', 'in:male,female'],
            'birth_date'     => ['nullable', 'date'],
        ];
    }

    // 3. رسائل الخطأ باللغة العربية
    public function messages(): array
    {
        return [
            'name.required'         => 'اسم الطالب مطلوب.',
            'username.required'     => 'اسم المستخدم مطلوب.',
            'username.unique'       => 'اسم المستخدم محجوز مسبقاً.',
            'email.required'        => 'البريد الإلكتروني مطلوب.',
            'email.email'           => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'          => 'البريد الإلكتروني مسجل مسبقاً.',
            'password.required'     => 'كلمة المرور مطلوبة.',
            'password.min'          => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
            'phone_number.unique'   => 'رقم الهاتف مسجل مسبقاً.',
            'status.in'             => 'حالة الحساب غير صالحة.',
            'grade_id.required'     => 'تحديد الصف الدراسي مطلوب.',
            'grade_id.exists'       => 'الصف الدراسي المحدد غير موجود في النظام.',
            'gender.in'             => 'الجنس يجب أن يكون ذكراً أو أنثى.',
            'birth_date.date'       => 'صيغة تاريخ الميلاد غير صحيحة.',
        ];
    }
}
