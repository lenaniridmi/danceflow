<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with('user', 'course')
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'author' => $review->user->name,
                    'avatar' => '/assets/avatar-placeholder.png', // Заменим на реальные аватарки позже
                    'rating' => $review->rating,
                    'text' => $review->comment,
                    'course_title' => $review->course->title_ru, // Для русского языка
                    'course_id' => $review->course->id,
                    'created_at' => $review->created_at->format('Y-m-d H:i'),
                    'likes' => 0, // Добавим позже
                    'dislikes' => 0, // Добавим позже
                    'liked' => false,
                    'disliked' => false,
                ];
            });

        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'string|nullable',
        ]);

        $review = $request->user()->reviews()->create($validated);
        return response()->json([
            'message' => 'Отзыв добавлен',
            'review' => [
                'id' => $review->id,
                'author' => $request->user()->name,
                'avatar' => '/assets/avatar-placeholder.png',
                'rating' => $review->rating,
                'text' => $review->comment,
                'course_title' => $review->course->title_ru,
                'course_id' => $review->course->id,
                'created_at' => $review->created_at->format('Y-m-d H:i'),
                'likes' => 0,
                'dislikes' => 0,
                'liked' => false,
                'disliked' => false,
            ],
        ], 201);
    }
}