<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray(Request $request): array
    {






        return [
            'id'             => $this->id,
            'specialization' => $this->specialization,
            'bio'            => $this->bio,
            'qualification'    => $this->qualification,
            'gender'         => $this->gender,
            'birth_date'     => $this->birth_date ? $this->birth_date->format('Y-m-d') : null,
            'graduation_year'  => $this->graduation_year,


             //  UserResource
            'profile'        => new UserResource($this->whenLoaded('user')),
            //   SubjectResource
            'subject'        => new SubjectResource($this->whenLoaded('subject')),
        ];
    }
}
