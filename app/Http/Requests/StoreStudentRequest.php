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
            // بيانات الحساب الأساسي (جدول users)
            'identity_number'  => ['required', 'string', 'unique:users,identity_number'],
            'first_name'       => ['required', 'string', 'max:255'],
            'father_name'      => ['required', 'string', 'max:255'],
            'grandfather_name' => ['required', 'string', 'max:255'],
            'family_name'      => ['required', 'string', 'max:255'],
            'username'         => ['required', 'string', 'unique:users,username'],
            'email'            => ['nullable', 'email', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8'],
            'phone_number'     => ['nullable', 'string', 'unique:users,phone_number'],
            'status'           => ['nullable', 'in:active,inactive'],

            // بيانات الطالب الإضافية (جدول students)
            'grade_id'         => ['required', 'integer', 'exists:grades,id'],
            'section'          => ['nullable', 'string', 'max:255'],  // الشعبة الدراسية
            'health_status'    => ['nullable', 'string', 'max:255'],  // الحالة الصحية
            'parent_phone'     => ['nullable', 'string'],
            'gender'           => ['nullable', 'in:male,female'],
            'birth_date'       => ['nullable', 'date'],
        ];
    }

    // 3. رسائل الخطأ باللغة العربية
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
            'grade_id.required'     => 'تحديد الصف الدراسي مطلوب.',
            'grade_id.exists'       => 'الصف الدراسي المحدد غير موجود في النظام.',
            'gender.in'             => 'الجنس يجب أن يكون ذكراً أو أنثى.',
            'birth_date.date'       => 'صيغة تاريخ الميلاد غير صحيحة.',
        ];
    }
}
