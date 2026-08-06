<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'gender',
        'birth_date',
        'qualification',
        'graduation_year',
        'specialization',
        'bio',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }



    // جلب الشعب التي يدرسها المعلم
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_teacher');
    }

    // جلب جميع طلاب هذا المعلم (عبر الشعب)
    public function students()
    {
        return Student::whereIn('section_id', $this->sections()->pluck('sections.id'));
    }
}
