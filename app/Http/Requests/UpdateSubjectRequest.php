<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject');

        return [
            'grade_id'    => ['required', 'integer', 'exists:grades,id'],
            'name'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')
                    ->where(function ($query) {
                        return $query->where('grade_id', $this->grade_id);
                    })
                    ->ignore($subjectId)
            ],
            'description' => ['nullable', 'string'],
            'status'      => ['nullable', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'grade_id.required' => 'الصف الدراسي مطلوب.',
            'grade_id.exists'   => 'الصف الدراسي المحدد غير موجود.',
            'name.required'     => 'اسم المادة مطلوب.',
            'name.unique'       => 'هذه المادة موجودة مسبقاً في هذا الصف.',
            'status.in'         => 'حالة المادة غير صالحة.',
        ];
    }
}
