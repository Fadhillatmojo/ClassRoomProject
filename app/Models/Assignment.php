<?php

namespace App\Models;

use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'class_id',
        'teacher_id',
        'due_date'
    ];

    // function relasional
    public function classRoom(){
        return $this->belongsTo(ClassRoom::class, 'class_id', 'id');
    }

    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
}
