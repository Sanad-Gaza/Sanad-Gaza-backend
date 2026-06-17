<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grade_id',
        'parent_phone',
        'gender',
        'birth_date',
        'points_balance',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'points_balance' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
