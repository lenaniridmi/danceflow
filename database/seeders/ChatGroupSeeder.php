<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatGroup;
use App\Models\User;

class ChatGroupSeeder extends Seeder
{
    public function run()
    {
        $student1 = User::where('email', 'student1@danceflow.com')->first();
        $student2 = User::where('email', 'student2@danceflow.com')->first();
        $student3 = User::where('email', 'student3@danceflow.com')->first();

        $group = ChatGroup::create([
            'name' => 'Dance Group',
            'avatar_url' => '/assets/group-avatar.jpg',
        ]);

        $group->members()->attach([$student1->id, $student2->id, $student3->id]);
    }
}