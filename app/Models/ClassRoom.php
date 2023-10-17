<?php

namespace App\Models;

use App\Models\User;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'teacher_id'
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'student_class', 'class_id', 'student_id');
    }
    public function assignments(){
        return $this->hasMany(Assignment::class, 'class_id', 'id');
    }
}
