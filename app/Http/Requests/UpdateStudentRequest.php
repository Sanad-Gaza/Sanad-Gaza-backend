<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Student;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // استخراج ID الطالب من الرابط
        $studentId = $this->route('id');
        $student = Student::findOrFail($studentId);

        return [
            'identity_number'  => ['required', 'string', Rule::unique('users', 'identity_number')->ignore($student->user_id)],
            'first_name'       => ['required', 'string', 'max:255'],
            'father_name'      => ['required', 'string', 'max:255'],
            'grandfather_name' => ['required', 'string', 'max:255'],
            'family_name'      => ['required', 'string', 'max:255'],
            'username'         => ['required', 'string', Rule::unique('users', 'username')->ignore($student->user_id)],
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($student->user_id)],

            // الباسوورد اختياري عند التحديث
            'password'         => ['nullable', 'string', 'min:8'],
            'phone_number'     => ['nullable', 'string'],
            'status'           => ['nullable', 'in:active,inactive'],

            // بيانات الطالب
            'grade_id'         => ['required', 'integer', 'exists:grades,id'],
            'section'          => ['nullable', 'string', 'max:255'],
            'health_status'    => ['nullable', 'string', 'max:255'],
            'parent_phone'     => ['nullable', 'string'],
            'gender'           => ['nullable', 'in:male,female'],
            'birth_date'       => ['nullable', 'date'],
        ];
    }
}
