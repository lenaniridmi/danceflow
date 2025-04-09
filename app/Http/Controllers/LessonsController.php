<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title_ru' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'video_url' => 'required|url',
            'duration' => 'required|integer',
            'order' => 'required|integer'
        ]);

        if ($request->user()->role !== 'teacher') {
            return back()->withErrors(['role' => 'Только преподаватели могут добавлять уроки']);
        }

        $lesson = Lesson::create($validated);
        return redirect()->route('courses.index')->with('success', 'Урок добавлен');
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);
        if ($request->user()->role !== 'teacher' || $lesson->course->teacher_id !== $request->user()->id) {
            return back()->withErrors(['role' => 'Вы не можете редактировать этот урок']);
        }

        $validated = $request->validate([
            'title_ru' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'video_url' => 'required|url',
            'duration' => 'required|integer',
            'order' => 'required|integer'
        ]);

        $lesson->update($validated);
        return redirect()->route('courses.index')->with('success', 'Урок обновлен');
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        if (auth()->user()->role !== 'teacher' || $lesson->course->teacher_id !== auth()->user()->id) {
            return back()->withErrors(['role' => 'Вы не можете удалить этот урок']);
        }

        $lesson->delete();
        return redirect()->route('courses.index')->with('success', 'Урок удален');
    }
}