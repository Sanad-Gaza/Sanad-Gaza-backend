<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'grade_id',
        'name',
        'description',
        'status',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
