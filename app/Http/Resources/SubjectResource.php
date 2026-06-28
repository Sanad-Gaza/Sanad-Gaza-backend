<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'grade_id'    => $this->grade_id,
            'name'        => $this->name,
            'description' => $this->description,
            'status'      => $this->status,
            'created_at'  => $this->created_at->toISOString(),
            'updated_at'  => $this->updated_at->toISOString(),
            // إذا أردت إظهار بيانات الصف المرتبط، يمكنك إضافة هذا السطر لاحقاً:
            // 'grade' => new GradeResource($this->whenLoaded('grade')),
        ];
    }
}
