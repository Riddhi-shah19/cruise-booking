<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PassengerController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::guard('passenger')->check()) {
            return redirect()->route('dashboard');
        }
    

        if ($request->isMethod('post')) {
            // Validate form data
            $request->validate([
                'id' => 'required|integer',
                'class' => 'required|in:general,luxury',
                'number' => 'required|integer|min:1',
            ]);

            // Retrieve form data
            $class = $request->input('class');
            $number = $request->input('number');
            $schedule_id = $request->input('id');
            $roomNumber = $request->input('room_number');
            $cruise_id=$request->input('cruise_id');

            // Fetch schedule details (replace with your actual logic)
            $schedule = Schedule::findOrFail($schedule_id);
            $route = getRoutePath($schedule->route_id);
            $fee = $class === 'general' ? $schedule->general_fee : $schedule->luxury_fee;
            $date = $schedule->date;
            $total = $fee * $number;
            $vat = ceil($total * 0.01);
            $grandTotal = $total + $vat;
          
            $room = DB::table('rooms')
          ->where('room_number', $roomNumber)
          ->where('cruise_id', $cruise_id)
          ->first();

            Session::put('amount', $grandTotal);
            Session::put('email', Auth::guard('passenger')->user()->email); 
            Session::put('schedule_id', $schedule_id); 
            Session::put('number', $number); 
            Session::put('room_id', $room->id);  
            Session::put('cruise_id', $cruise_id);  
            Session::put('date', $date);  

            return view('passenger.dashboard', compact('class', 'number', 'route', 'fee', 'total', 'vat', 'grandTotal', 'schedule_id'));
        }
        $schedules = Schedule::whereDate('date', '>=', Carbon::today())->get()->map(function ($schedule) {
            return [
                'title' => getRouteFromSchedule($schedule->id),
                'start' => $schedule->date,
                'time' => $schedule->time,
                'color' => '#28a745'
            ];
        });
    
        return view('passenger.dashboard', compact('schedules'));
    }

    /**
     * Show the New Booking page.
     */
    public function booking()
    {
        return view('passenger.booking');
    }

    /**
     * Show the View Bookings page.
     */
    public function bookings()
    {
        return view('passenger.ticket', );
    }

    /**
     * Show the Feedback page.
     */
    public function feedback()
    {
        return view('passenger.feedbacks'); 
    }

    public function logout(Request $request)
    {
        Session::flush();
        Auth::guard('passenger')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You are being logged out');
    }
}
