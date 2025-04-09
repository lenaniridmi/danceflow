<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'timestamp' => 'required|integer',
            'text' => 'required|string'
        ]);

        $note = $request->user()->notes()->create($validated);
        return redirect()->back()->with('success', 'Заметка добавлена');
    }
}