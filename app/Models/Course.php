<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title_ru', 'title_en', 'description_ru', 'description_en', 'style', 'level', 'teacher_id'
    ];

    protected $casts = [
        'level' => 'string',
        'created_at' => 'datetime'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'course_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'course_id');
    }
}