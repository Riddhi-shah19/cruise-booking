<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('user')->orderBy('response')->get();
        return view('admin.pages.feedbacks', compact('feedbacks'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:3',
        ]);

        $feedback = Feedback::findOrFail($id);
        $feedback->response = $request->reply;
        $feedback->save();

        return redirect()->back()->with('success', 'Reply sent!');
    }
}
