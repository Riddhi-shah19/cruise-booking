<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PaymentController extends Controller
{

    public function index()
    {
        $payments = DB::table('schedule')
            ->join('payment', 'schedule.id', '=', 'payment.schedule_id')
            ->select('schedule.*', 'schedule.id as schedule_id', 'schedule.date', 'schedule.time')
            ->get();

        foreach ($payments as $payment) {
            $payment->route = getRoutePath($payment->route_id);
            $payment->formatted_time = formatTime($payment->time);
            $payment->sum_first = sum($payment->schedule_id, 'general');
            $payment->sum_second = sum($payment->schedule_id, 'luxury');
            $capacity = getTotalBookByType($payment->schedule_id);
            $payment->first_class_avail = $capacity['first'] - $capacity['first_booked'];
            $payment->second_class_avail = $capacity['second'] - $capacity['second_booked'];
        }

        return view('admin.pages.payments', compact('payments'));
    }

}
