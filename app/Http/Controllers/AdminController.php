<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Progress;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function analytics(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return back()->withErrors(['role' => 'Только администраторы могут просматривать аналитику']);
        }

        $courseStats = Course::withCount('progress')->get();
        $userCount = User::count();
        $completedLessons = Progress::where('completed', true)->count();

        return view('admin.analytics', compact('courseStats', 'userCount', 'completedLessons'));
    }
}