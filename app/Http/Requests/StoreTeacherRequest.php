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
            // بيانات المستخدم الأساسية
            'identity_number'  => ['required', 'string', 'unique:users,identity_number'],
            'first_name'       => ['required', 'string', 'max:255'],
            'father_name'      => ['required', 'string', 'max:255'],
            'grandfather_name' => ['required', 'string', 'max:255'],
            'family_name'      => ['required', 'string', 'max:255'],
            'username'         => ['required', 'string', 'unique:users,username'],
            'email'            => ['nullable', 'email', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8'],
            'phone_number'     => ['nullable', 'string'],
            'status'           => ['nullable', 'in:active,inactive'],
            

            // بيانات المعلم الإضافية
            'subject_id'       => ['required', 'integer', 'exists:subjects,id'],
            'gender'           => ['nullable', 'in:male,female'],
            'birth_date'       => ['nullable', 'date'],
            'qualification'    => ['nullable', 'string', 'max:255'],
            'graduation_year'  => ['nullable', 'digits:4'],
            'specialization'   => ['nullable', 'string', 'max:255'],
            'bio'              => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'identity_number.required'  => 'رقم الهوية مطلوب.',
            'first_name.required'       => 'الاسم الأول مطلوب.',
            'father_name.required'      => 'اسم الأب مطلوب.',
            'grandfather_name.required' => 'اسم الجد مطلوب.',
            'family_name.required'      => 'اسم العائلة مطلوب.',
            'username.required'         => 'اسم المستخدم مطلوب.',
            'username.unique'           => 'اسم المستخدم محجوز مسبقاً.',
            'email.required'            => 'البريد الإلكتروني مطلوب.',
            'email.email'               => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique'              => 'البريد الإلكتروني مسجل مسبقاً.',
            'password.required'           => 'كلمة المرور مطلوبة.',
            'password.min'                => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
            'phone_number.unique' => 'رقم الهاتف مسجل مسبقاً.',
            'status.in'           => 'حالة الحساب غير صالحة.',
            'subject_id.required' => 'تحديد المادة الدراسية مطلوب.',
            'subject_id.exists'   => 'المادة الدراسية المحددة غير موجودة في النظام.',
        ];
    }
}
