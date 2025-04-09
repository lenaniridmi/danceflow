<?php

namespace App\Models;

// Используем Authenticatable для сессионной аутентификации
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email', 'password', 'name', 'role', 'language'
    ];

    protected $casts = [
        'role' => 'string', // ENUM как строка
        'language' => 'string',
        'created_at' => 'datetime'
    ];

    protected $hidden = [
        'password' // Скрываем пароль в ответах
    ];

    // Связи с другими моделями
    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'user_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }
}