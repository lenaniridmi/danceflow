<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('teacher');
        if ($request->has('style')) $query->where('style', $request->style);
        if ($request->has('level')) $query->where('level', $request->level);
        $courses = $query->get();

        return view('courses.index', compact('courses'));
    }

    public function apiIndex(Request $request)
    {
        $perPage = 15; // Ограничение в 15 курсов на страницу
        $query = Course::with('teacher');

        // Фильтрация по категории (style), если указана
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('style', $request->category);
        }

        // Пагинация
        $courses = $query->paginate($perPage);

        $user = $request->user();
        $coursesData = $courses->map(function ($course) use ($user) {
            $isFavorite = $user ? $user->favorites()->where('course_id', $course->id)->exists() : false;
            return [
                'id' => $course->id,
                'title' => $course->title_ru,
                'description' => $course->description_ru,
                'image' => $course->thumbnail_url,
                'category' => $course->style,
                'level' => $course->level,
                'teacher' => $course->teacher ? $course->teacher->name : 'Unknown',
                'isFavorite' => $isFavorite,
            ];
        });

        return response()->json([
            'data' => $coursesData,
            'current_page' => $courses->currentPage(),
            'last_page' => $courses->lastPage(),
            'total' => $courses->total(),
        ]);
    }

    public function show($id, Request $request)
    {
        $course = Course::with('teacher')->findOrFail($id);
        $user = $request->user();
        $isFavorite = $user ? $user->favorites()->where('course_id', $course->id)->exists() : false;

        return response()->json([
            'id' => $course->id,
            'title' => $course->title_ru,
            'description' => $course->description_ru,
            'image' => $course->thumbnail_url,
            'category' => $course->style,
            'level' => $course->level,
            'teacher' => $course->teacher ? $course->teacher->name : 'Unknown',
            'isFavorite' => $isFavorite,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ru' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ru' => 'string|nullable',
            'description_en' => 'string|nullable',
            'style' => 'required|string|max:50',
            'level' => 'required|in:beginner,intermediate,advanced',
        ]);

        if ($request->user()->role !== 'teacher') {
            return back()->withErrors(['role' => 'Только преподаватели могут создавать курсы']);
        }

        $course = $request->user()->courses()->create($validated);
        return redirect()->route('courses.index')->with('success', 'Курс создан');
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        if ($request->user()->role !== 'teacher' || $course->teacher_id !== $request->user()->id) {
            return back()->withErrors(['role' => 'Вы не можете редактировать этот курс']);
        }

        $validated = $request->validate([
            'title_ru' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ru' => 'string|nullable',
            'description_en' => 'string|nullable',
            'style' => 'required|string|max:50',
            'level' => 'required|in:beginner,intermediate,advanced',
        ]);

        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Курс обновлен');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        if (auth()->user()->role !== 'teacher' || $course->teacher_id !== auth()->user()->id) {
            return back()->withErrors(['role' => 'Вы не можете удалить этот курс']);
        }

        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Курс удален');
    }
}