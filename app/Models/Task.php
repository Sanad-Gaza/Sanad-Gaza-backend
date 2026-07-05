<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'points',
        'due_date',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_task')->withPivot('status')->withTimestamps();
    }
}
