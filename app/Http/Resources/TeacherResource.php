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
             //  UserResource
            'profile'        => new UserResource($this->whenLoaded('user')),
            //   SubjectResource
            'subject'        => new SubjectResource($this->whenLoaded('subject')),
        ];
    }
}
