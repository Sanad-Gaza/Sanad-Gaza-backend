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
        'section',
        'health_status',
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

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'student_task')->withPivot('status')->withTimestamps();
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'student_unit')
            ->withPivot('status', 'stars')
            ->withTimestamps();
    }
}
