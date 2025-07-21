<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::where('user_id', Auth::guard('passenger')->user()->id)->latest()->get(); 
        return view('passenger.feedbacks', compact('feedbacks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|min:10',
        ]);

        $feedback = new Feedback();
        $feedback->user_id = Auth::guard('passenger')->user()->id;
        $feedback->message = $request->message;
        $feedback->save();

        return redirect()->back()->with('success', 'Feedback sent! We will get back to you.');
    }
}
