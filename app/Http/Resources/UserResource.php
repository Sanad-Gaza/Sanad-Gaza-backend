<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
  public function toArray(Request $request): array
{

        return [
            'id'               => $this->id,
            'identity_number'  => $this->identity_number,

            // الحقول المنفصلة (قد يحتاجها الـ Frontend في نماذج التعديل)
            'first_name'       => $this->first_name,
            'father_name'      => $this->father_name,
            'grandfather_name' => $this->grandfather_name,
            'family_name'      => $this->family_name,

            // دمج الاسم الكامل في حقل واحد كما طلبت!
            'full_name'        => trim("{$this->first_name} {$this->father_name} {$this->grandfather_name} {$this->family_name}"),

            'username'         => $this->username,
            'email'            => $this->email,
            'phone_number'     => $this->phone_number,
            'role'             => $this->role,
            'status'           => $this->status,
            'profile_picture'  => $this->profile_picture,
            'created_at'       => $this->created_at->toISOString(),
        ];
    }

}
