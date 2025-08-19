<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function handle(Request $request){
        $question = $request->input('message');
        $response = $this->askAIWithContext($question);
        return response()->json(['reply'=>$response]);
    }
}
