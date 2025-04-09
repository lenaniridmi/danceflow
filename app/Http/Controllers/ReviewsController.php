<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'string|nullable'
        ]);

        $review = $request->user()->reviews()->create($validated);
        return redirect()->back()->with('success', 'Отзыв добавлен');
    }
}