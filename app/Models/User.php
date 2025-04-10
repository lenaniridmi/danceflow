<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email', 'password', 'name', 'role', 'language', 'avatar_url',
    ];

    protected $casts = [
        'role' => 'string',
        'language' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

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

    public function chatGroups()
    {
        return $this->belongsToMany(ChatGroup::class, 'chat_group_members', 'user_id', 'group_id')
            ->withTimestamps();
    }
}