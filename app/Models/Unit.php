<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
   protected $fillable = ['subject_id', 'title', 'description', 'status'];

    // الوحدة تنتمي لمادة دراسية
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // الوحدة تحتوي على عدة مهام
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
