<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Passenger;
use App\Models\Cruise;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['cruise', 'route'])->get();
        $cruise = Cruise::all();
        $routes = Route::all();

        return view('admin.pages.schedules', compact('schedules', 'cruise', 'routes'));
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->only([
            'cruise_id', 'route_id', 'general_fee', 'luxury_fee', 'date', 'time'
        ]));

        $passengers = Passenger::join('booked', 'booked.user_id', '=', 'passenger.id')
        ->where('booked.schedule_id', $id)
        ->pluck('email');

        $msg = "Having considered user satisfaction...<hr/> New Date: {$request->date}<br/>New Time: {$request->time}<hr/>";

        foreach ($passengers as $email) {
            Mail::raw($msg, function ($message) use ($email) {
                $message->to($email)->subject('Change In Trip Date/Time');
            });
    }

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

    public function create()
    {
        $trains = Cruise::all();
        $routes = Route::all();
        return view('admin.pages.schedules', compact('trains', 'routes'));
    }

    public function destroy($id)
    {
        Schedule::destroy($id);
        return redirect()->back()->with('success', 'Schedule deleted successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cruise_id' => 'required|exists:cruise,id',
            'route_id' => 'required|exists:route,id',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'general_fee' => 'required|numeric',
            'luxury_fee' => 'required|numeric',
        ]);

        Schedule::create([
            'cruise_id' => $request->cruise_id,
            'route_id' => $request->route_id,
            'date' => $request->date,
            'time' => $request->time,
            'general_fee' => $request->general_fee,
            'luxury_fee' => $request->luxury_fee,
        ]);

        return redirect()->back()->with('success', 'Schedule added successfully!');
    }

    public function storeRange(Request $request)
    {
        $request->validate([
            'cruise_id' => 'required|exists:cruise,id',
            'route_id' => 'required|exists:route,id',
            'general_fee' => 'required|integer|min:0',
            'luxury_fee' => 'required|integer|min:0',
            'from_date' => 'required|date|after:today',
            'to_date' => 'required|date|after_or_equal:from_date',
            'time' => 'required|date_format:H:i',
            'every' => 'required|string'
        ]);

        $cruise_id = $request->cruise_id;
        $route_id = $request->route_id;
        $general_fee = $request->general_fee;
        $luxury_fee = $request->luxury_fee;
        $from = Carbon::parse($request->from_date);
        $to = Carbon::parse($request->to_date);
        $time = $request->time;
        $every = $request->every;

        // Handle daily or specific weekday scheduling
        while ($from->lte($to)) {
            if ($every === 'Day' || $from->englishDayOfWeek === $every) {
                Schedule::create([
                    'cruise_id' => $cruise_id,
                    'route_id' => $route_id,
                    'date' => $from->format('Y-m-d'),
                    'time' => $time,
                    'general_fee' => $general_fee,
                    'luxury_fee' => $luxury_fee
                ]);
            }
            $from->addDay();
        }

        return redirect()->back()->with('success', 'Range Schedule Added Successfully!');
    }
}
