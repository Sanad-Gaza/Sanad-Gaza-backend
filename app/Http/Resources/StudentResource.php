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
            'birth_date'     => $this->birth_date ? $this->birth_date->format('Y-m-d') : null,
            'points_balance' => $this->points_balance,
            'points_earned'  => $this->points_earned,
            'health_status'  => $this->health_status,
            'section'        => $this->section,

            'profile'        => new UserResource($this->whenLoaded('user')),
            'grade'          => new GradeResource($this->whenLoaded('grade')),

            'created_at'     => $this->created_at->toISOString(),
        ];
    }
}
