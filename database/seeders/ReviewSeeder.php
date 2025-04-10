<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Course;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $student1 = User::where('email', 'student1@danceflow.com')->first();
        $student2 = User::where('email', 'student2@danceflow.com')->first();
        $student3 = User::where('email', 'student3@danceflow.com')->first();

        $course1 = Course::where('title_en', 'Hip-Hop Basics')->first();
        $course2 = Course::where('title_en', 'Intermediate Salsa')->first();
        $course3 = Course::where('title_en', 'Contemporary Dance')->first();

        Review::create([
            'user_id' => $student1->id,
            'course_id' => $course1->id,
            'rating' => 5,
            'comment' => 'This course was amazing! I learned so much about hip-hop.',
            'created_at' => now()->subDays(5),
        ]);

        Review::create([
            'user_id' => $student2->id,
            'course_id' => $course2->id,
            'rating' => 4,
            'comment' => 'Great salsa course, but I wish there were more lessons.',
            'created_at' => now()->subDays(3),
        ]);

        Review::create([
            'user_id' => $student3->id,
            'course_id' => $course3->id,
            'rating' => 3,
            'comment' => 'The contemporary course was okay, but the instructor could be more engaging.',
            'created_at' => now()->subDays(1),
        ]);
    }
}