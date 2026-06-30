<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Teacher;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // استخراج ID المعلم من الرابط
        $teacherId = $this->route('id');
        $teacher = Teacher::findOrFail($teacherId);

        return [
            'name'           => ['required', 'string', 'max:255'],
            // نستثني الـ user_id الخاص بالمعلم من فحص التكرار
            'username'       => ['required', 'string', Rule::unique('users', 'username')->ignore($teacher->user_id)],
            'email'          => ['required', 'email', Rule::unique('users', 'email')->ignore($teacher->user_id)],
            // الباسوورد nullable لأنه قد لا يرغب بتغييره عند تحديث بياناته
            'password'       => ['nullable', 'string', 'min:8'],
            'phone_number'   => ['nullable', 'string', Rule::unique('users', 'phone_number')->ignore($teacher->user_id)],
            'status'         => ['nullable', 'in:active,inactive'],
            'subject_id'     => ['required', 'integer', 'exists:subjects,id'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio'            => ['nullable', 'string'],
        ];
    }
}
