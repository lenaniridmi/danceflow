<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';
    protected $primaryKey = 'id';

    protected $fillable = [
        'course_id', 'title_ru', 'title_en', 'video_url', 'duration', 'order'
    ];

    protected $casts = [
        'duration' => 'integer',
        'order' => 'integer'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'lesson_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'lesson_id');
    }
}