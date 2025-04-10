<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $teacher1 = User::where('email', 'teacher1@danceflow.com')->first();
        $teacher2 = User::where('email', 'teacher2@danceflow.com')->first();

        Course::create([
            'title_ru' => 'Основы хип-хопа',
            'title_en' => 'Hip-Hop Basics',
            'description_ru' => 'Курс для начинающих, чтобы освоить основы хип-хопа.',
            'description_en' => 'A beginner course to master the basics of hip-hop.',
            'style' => 'hip-hop',
            'level' => 'beginner',
            'teacher_id' => $teacher1->id,
            'thumbnail_url' => '/assets/course-hiphop.jpg',
        ]);

        Course::create([
            'title_ru' => 'Сальса для продолжающих',
            'title_en' => 'Intermediate Salsa',
            'description_ru' => 'Курс для продолжающих, чтобы улучшить навыки сальсы.',
            'description_en' => 'An intermediate course to improve your salsa skills.',
            'style' => 'salsa',
            'level' => 'intermediate',
            'teacher_id' => $teacher1->id,
            'thumbnail_url' => '/assets/course-salsa.jpg',
        ]);

        Course::create([
            'title_ru' => 'Современные танцы',
            'title_en' => 'Contemporary Dance',
            'description_ru' => 'Курс для всех уровней, чтобы изучить современные танцы.',
            'description_en' => 'A course for all levels to learn contemporary dance.',
            'style' => 'contemporary',
            'level' => 'beginner',
            'teacher_id' => $teacher2->id,
            'thumbnail_url' => '/assets/course-contemporary.jpg',
        ]);
    }
}