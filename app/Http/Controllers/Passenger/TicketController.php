<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booked;
use App\Models\Schedule;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use TCPDF;


class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::guard('passenger')->user();

        $bookings = Booked::with(['payment', 'schedule','room'])
            ->whereHas('payment', fn($q) => $q->where('passenger_id', $user->id))
            ->latest('id')
            ->get();

        $upcomingSchedules = Schedule::whereDate('date', '>=', now()->format('Y-m-d'))->get();

        return view('passenger.ticket', compact('bookings', 'upcomingSchedules'));
    }

    public function modify(Request $request)
    {
        $request->validate([
            'pk' => 'required|exists:booked,id',
            's' => 'required|exists:schedule,id',
        ]);

        Booked::where('id', $request->pk)->update(['schedule_id' => $request->s]);

        return redirect()->route('passenger.tickets')->with('success', 'Booking modified successfully.');
    }

    public function printTicket($id)
    {
        $booking = DB::table('booked')
            ->join('schedule', 'booked.schedule_id', '=', 'schedule.id')
            ->join('payment', 'payment.id', '=', 'booked.payment_id')
            ->join('passenger', 'passenger.id', '=', 'booked.user_id')
            ->join('rooms','rooms.cruise_id','=','schedule.cruise_id')
            ->select(
                'schedule.id as schedule_id',
                'passenger.name as fullname',
                'passenger.email',
                'passenger.phone',
                'passenger.loc',
                'payment.amount',
                'payment.ref',
                'payment.date as payment_date',
                'schedule.cruise_id',
                'booked.code',
                'booked.rooms_booked',
                'rooms.type as class',
                'rooms.room_number as seat',
                'schedule.date',
                'schedule.time'
            )
            ->where('booked.id', $id)
            ->first();

        if (!$booking) {
            abort(403, 'Access Denied');
        }

        $name = $booking->fullname;
        $barcodeText = "{$name} Ticket For " . getRouteFromSchedule($booking->schedule_id) . " - " . date("D d, M Y", strtotime($booking->date)) . " by " . formatTime($booking->time) . ". Ticket ID : " . $booking->code;
        $qrPath = generateQR($id, $barcodeText); // assumes generateQR returns a PNG path
        $file_name = preg_replace('/[^a-z0-9]+/', '-', strtolower(substr($name, 0, 15))) . ".pdf";

        // Setup TCPDF
        $pdf = new class('P', 'mm', 'A4', true, 'UTF-8', false) extends TCPDF {
            public function Header()
            {
                $this->SetAutoPageBreak(false, 0);
                $img_file = public_path('images/watermark.jpg');
                $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                $this->SetAlpha(0.5);
                $this->setPageMark();
            }
        };

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($name);
        $pdf->SetTitle($name . " Ticket");
        $pdf->SetSubject(config('app.name'));
        $pdf->SetMargins(PDF_MARGIN_LEFT, 7, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(true, 5);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 14);
        $pdf->AddPage();

        $route = getRouteFromSchedule($booking->schedule_id);
        $train = getTrainName($booking->cruise_id);
        $class = $booking->class;
        $seat = $booking->seat;
        $paymentDate = $booking->payment_date;
        $amount = $booking->amount;
        $date = date("D d, M Y", strtotime($booking->date));
        $time = formatTime($booking->time);
        $photoPath = public_path('uploads/' . $booking->loc);

        $html = <<<EOD
        <style> table th { font-weight: italic } </style>
        <h1 style="text-align:center">
            <img src="images/trainlg.png" width="100" height="100"/><br/>
            ONLINE TICKET RESERVATION SYSTEM<br/>
            CRUISE TICKET
        </h1>
        <div style="text-align:right; font-family:courier;font-weight:bold">
            <font size="+6">Ticket N<u>o</u>: {$booking->code} </font>
        </div>
        <table width="100%" border="1">
        <tr><th colspan="2" style="text-align:center"><b>Personal Data</b></th></tr>
        <tr><th>Full Name:</th><td>{$booking->fullname}</td></tr>
        <tr><th>Email:</th><td>{$booking->email}</td></tr>
        <tr><th>Contact:</th><td>{$booking->phone}</td></tr>
        <tr><th colspan="2" style="text-align:center">Trip Detail</th></tr>
        <tr><th>Route:</th><td>$route</td></tr>
        <tr><th>Cruise:</th><td>$train</td></tr>
        <tr><th>Class:</th><td>{$class} Class</td></tr>
        <tr><th>Seat Number:</th><td>{$seat}</td></tr>
        <tr><th>Date:</th><td>{$date}</td></tr>
        <tr><th>Time:</th><td>{$time}</td></tr>
        <tr><th colspan="2" style="text-align:center">Payment</th></tr>
        <tr><th>Amount:</th><td>\Rs {$amount}</td></tr>
        <tr><th>Payment Date:</th><td>{$paymentDate}</td></tr>
        </table>
        <table width="100%">
        <tr><td colspan="2" style="text-align:center"><i><strong>CAUTION:</strong> Ticket forgery is punishable by law.</i></td></tr>
        <tr><td colspan="2" style="text-align:center"><i><strong>NOTE:</strong> Arrive 1 hour early.</i></td></tr>
        <tr>
        <td style="text-align:left"><img width="180" height="180" src="{$photoPath}"></td>
        <td style="text-align:right"><img width="180" height="180" src="{$qrPath}"></td>
        </tr>
        </table>
        EOD;

            $pdf->writeHTML($html, true, false, true, false, '');
            @unlink($qrPath); // remove QR after use
            return response($pdf->Output($file_name, 'S'))->header('Content-Type', 'application/pdf');
    }
}
