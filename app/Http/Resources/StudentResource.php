<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'gender'         => $this->gender,
            'birth_date'     => $this->birth_date ?? null,
            'points_balance' => $this->points_balance,
            'points_earned'  => $this->points_earned,
            'health_status'  => $this->health_status,
            'section'        => $this->section,

            'profile'        => new UserResource($this->whenLoaded('user')),
            'grade'          => new GradeResource($this->whenLoaded('grade')),
            //only Student Grade Subjects will be returned, not all subjects
            'subjects'       => SubjectResource::collection($this->whenLoaded('grade')->subjects()->get()),

            'created_at'     => $this->created_at->toISOString(),
        ];
    }
}
