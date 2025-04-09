<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $favorite = Favorite::firstOrCreate(
            ['user_id' => $request->user()->id, 'course_id' => $validated['course_id']]
        );

        return redirect()->back()->with('success', 'Курс добавлен в избранное');
    }

    public function index(Request $request)
    {
        $favorites = $request->user()->favorites()->with('course')->get();
        return view('favorites.index', compact('favorites'));
    }

    public function destroy($courseId)
    {
        $favorite = Favorite::where('user_id', auth()->user()->id)
            ->where('course_id', $courseId)
            ->firstOrFail();
        $favorite->delete();

        return redirect()->back()->with('success', 'Курс удален из избранного');
    }
}