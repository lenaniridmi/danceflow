<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'course_id', 'rating', 'comment', 'likes', 'dislikes',
    ];

    protected $casts = [
        'rating' => 'integer',
        'likes' => 'integer',
        'dislikes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}