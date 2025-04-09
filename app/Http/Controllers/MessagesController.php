<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string'
        ]);

        $message = $request->user()->sentMessages()->create($validated);
        return redirect()->back()->with('success', 'Сообщение отправлено');
    }

    public function index(Request $request)
    {
        $messages = Message::where('sender_id', $request->user()->id)
            ->orWhere('receiver_id', $request->user()->id)
            ->with('sender', 'receiver')
            ->get();
        return view('messages.index', compact('messages'));
    }
}