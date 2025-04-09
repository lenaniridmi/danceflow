<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'lesson_id', 'timestamp', 'text'
    ];

    protected $casts = [
        'timestamp' => 'integer',
        'created_at' => 'datetime'
    ];

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}