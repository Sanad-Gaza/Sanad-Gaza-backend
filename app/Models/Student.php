<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grade_id',
        'section_id',
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




    public function scopeTopPerformers($query, $limit = 3)
    {
        return $query->orderBy('points_balance', 'desc')
            ->orderBy('daily_streak', 'desc')
            ->take($limit);
    }


    public function scopeLowActivity($query, $days = 7)
    {
        return $query->where('last_activity_date', '<', Carbon::now()->subDays($days))
            ->orWhereNull('last_activity_date');
    }

    public function section()
{
    return $this->belongsTo(Section::class);
}

public function subjects()
{
    return $this->belongsToMany(Subject::class, 'student_subject')
                ->withTimestamps();
}
}
