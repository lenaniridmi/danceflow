<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Администратор
        User::create([
            'email' => 'admin@danceflow.com',
            'password' => bcrypt('admin123'),
            'name' => 'Admin User',
            'role' => 'admin',
            'language' => 'en',
            'avatar_url' => '/assets/avatar-admin.jpg',
        ]);

        // Преподаватели
        User::create([
            'email' => 'teacher1@danceflow.com',
            'password' => bcrypt('teacher123'),
            'name' => 'Anna Smith',
            'role' => 'teacher',
            'language' => 'en',
            'avatar_url' => '/assets/avatar-anna.jpg',
        ]);

        User::create([
            'email' => 'teacher2@danceflow.com',
            'password' => bcrypt('teacher123'),
            'name' => 'Max Johnson',
            'role' => 'teacher',
            'language' => 'ru',
            'avatar_url' => '/assets/avatar-max.jpg',
        ]);

        // Ученики
        User::create([
            'email' => 'student1@danceflow.com',
            'password' => bcrypt('student123'),
            'name' => 'Katya Ivanova',
            'role' => 'student',
            'language' => 'ru',
            'avatar_url' => '/assets/avatar-katya.jpg',
        ]);

        User::create([
            'email' => 'student2@danceflow.com',
            'password' => bcrypt('student123'),
            'name' => 'Alex Brown',
            'role' => 'student',
            'language' => 'en',
            'avatar_url' => '/assets/avatar-alex.jpg',
        ]);

        User::create([
            'email' => 'student3@danceflow.com',
            'password' => bcrypt('student123'),
            'name' => 'Maria Petrova',
            'role' => 'student',
            'language' => 'ru',
            'avatar_url' => '/assets/avatar-maria.jpg',
        ]);
    }
}