<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Lesson;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(Request $request, $lessonId)
    {
        $validated = $request->validate([
            'completed' => 'required|boolean',
            'score' => 'integer|nullable|min:0|max:100'
        ]);

        $progress = Progress::updateOrCreate(
            ['user_id' => $request->user()->id, 'lesson_id' => $lessonId],
            [
                'course_id' => Lesson::findOrFail($lessonId)->course_id,
                'completed' => $validated['completed'],
                'completed_at' => $validated['completed'] ? now() : null,
                'score' => $validated['score'] ?? null
            ]
        );

        return redirect()->back()->with('success', 'Прогресс обновлен');
    }

    public function index(Request $request)
    {
        $progress = $request->user()->progress()->with('course', 'lesson')->get();
        return view('progress.index', compact('progress'));
    }
}