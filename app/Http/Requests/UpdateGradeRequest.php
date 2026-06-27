<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $gradeId = $this->route('grade');

        return [
            'name'=> [
                'required',
                'string',
                'max:255',
                Rule::unique('grades')->ignore($gradeId)
            ],
            'level'=> [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('grades')->ignore($gradeId)
            ],
            'description'=> ['nullable', 'string'],
            'status'=> ['nullable', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'اسم الصف مطلوب.',
            'name.unique'     => 'اسم الصف موجود مسبقاً.',
            'level.required'  => 'مستوى الصف (الرقم) مطلوب.',
            'level.unique'    => 'هذا المستوى موجود مسبقاً.',
            'level.min'       => 'المستوى يجب أن يكون 1 على الأقل.',
            'level.max'       => 'المستوى يجب أن لا يتجاوز 12.',
            'status.in'       => 'حالة الصف غير صالحة.',
        ];
    }
}
