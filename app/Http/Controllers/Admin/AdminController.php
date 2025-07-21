<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController
{
    public function dashboard() {
        return view('admin.pages.dashboard', [
            'fullname' => auth()->user()->name ?? 'Admin', // or get from session
            'passengerCount' => \DB::table('passenger')->count(),
            'trainCount' => \DB::table('cruise')->count(),
            'scheduleCount' => \DB::table('schedule')->count(),
            'totalPayments' => \DB::table('payment')->sum('amount'),
            'routeCount' => \DB::table('route')->count(),
            'feedbackCount' => \DB::table('feedback')->count(),
        ]);
    }

    public function users() {
        return view('admin.pages.users');
    }

    public function schedules() {
        return view('admin.pages.schedules');
    }

    public function routes() {
        return view('admin.pages.routes');
    }

    public function cruise() {
        return view('admin.pages.cruise');
    }

    public function reports() {
        return view('admin.pages.reports');
    }

    public function payments() {
        return view('admin.pages.payments');
    }

    public function feedbacks() {
        return view('admin.pages.feedbacks');
    }


    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect('/')->with('success', 'You are being logged out');
    }
}
