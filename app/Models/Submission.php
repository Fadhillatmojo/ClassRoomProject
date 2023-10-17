<?php

namespace App\Models;

use App\Models\User;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;
    protected $fillable = [
        'file',
        'text',
        'student_id',
        'assignment_id'
    ];

    public function student(){
        return $this->belongsTo(User::class, 'student_id', 'id');
    }
    public function assignment(){
        return $this->belongsTo(Assignment::class, 'assignment_id', 'id');
    }
}


