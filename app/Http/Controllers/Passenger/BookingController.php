<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function showBookingPage()
    {
        $schedules = Schedule::with('route')
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return view('passenger.booking', compact('schedules'));
    }

    public function getAvailableRooms(Request $request)
    {
        $request->validate([
            'cruise_id' => 'required|integer',
            'type' => 'required|string'
        ]);

        $bookedRoomIds = DB::table('booked')->pluck('room_id')->toArray();

        $rooms = DB::table('rooms')
            ->where('cruise_id', $request->cruise_id)
            ->where('type', $request->type)
            ->whereNotIn('id', $bookedRoomIds)
            ->pluck('room_number');

        return response()->json(['rooms' => $rooms]);
    }

   
    public function initiatePayment(Request $request)
    {
        if (!Session::has('amount') || !Session::has('email')) {
            Session::flush();
            return redirect('/')->with('error', 'Session expired');
        }

        $userId = Auth::guard('passenger')->user()->id; 
        $amount = Session::get('amount');
        $room_id = Session::get('room_id');
        $schedule_id = Session::get('schedule_id');
        $cruise_id = Session::get('cruise_id');
        $number = Session::get('number');
        $date = Session::get('date');

        $apiKey = config('services.phonepe.api_key');
        $saltIndex = config('services.phonepe.salt_index');
        $merchantId = config('services.phonepe.merchant_id');
        $merchantTransactionId = 'MT' . uniqid();

        DB::table('payment')->insert([
            'passenger_id' => $userId,
            'cruise_id' => $cruise_id,
            'room_id' => $room_id,
            'rooms_booked' => $number,
            'schedule_id' => $schedule_id,
            'amount' => $amount * 100,
            'ref' => $merchantTransactionId,
            'date' => $date,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $email = Session::get('email');
        $name = getIndividualName($userId); 

        $paymentData = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $merchantTransactionId,
            'merchantUserId' => 'M-' . uniqid(),
            'amount' => $amount * 100,
            'redirectUrl' => route('payment.verify', ['txn' => $merchantTransactionId]),
            'redirectMode' => 'POST',
            'callbackUrl' => route('payment.verify', ['txn' => $merchantTransactionId]),
            'merchantOrderId' => '1234',
            'message' => 'Order description',
            'email' => $email,
            'shortName' => $name,
            'paymentInstrument' => [
                'type' => 'PAY_PAGE',
            ],
        ];

        $jsonEncode = json_encode($paymentData);
        $payloadMain = base64_encode($jsonEncode);
        $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
        $sha256 = hash("sha256", $payload);
        $finalXHeader = $sha256 . '###' . $saltIndex;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $finalXHeader,
            'accept' => 'application/json'
        ])->post('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay', [
            'request' => $payloadMain
        ]);

        $res = $response->object();
        // dd($res);

        if ($res->success ?? false) {
            return redirect()->away($res->data->instrumentResponse->redirectInfo->url);
        }

        return back()->with('error', 'Payment initiation failed');
    }

    public function verifyPayment(Request $request)
    {

        $merchantTransactionId = $request->query('txn');

        if (!$merchantTransactionId) {
            return redirect('/')->with('error', 'Transaction ID missing.');
        }
    
        $pending = DB::table('payment')->where('ref', $merchantTransactionId)->first();
        $room = DB::table('rooms')->where('id', $pending->room_id)->first();
        $class=$room->type;
        
        if (!$pending) {
            return redirect('/')->with('error', 'Transaction not found.');
        }

        $merchantId = $request->merchantId;

        $apiKey = config('services.phonepe.api_key');
        $salt_index = config('services.phonepe.salt_index');

        $statusPath = "/pg/v1/status/{$merchantId}/{$merchantTransactionId}";
        $checksum = hash("sha256", $statusPath . $apiKey) . "###" . $salt_index;
        
        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "accept" => "application/json",
            "X-VERIFY" => $checksum,
            "X-MERCHANT-ID" => $merchantId
        ])->get("https://api-preprod.phonepe.com/apis/pg-sandbox" . $statusPath);

        $responsePayment = $response->json();
        // dd($responsePayment);

        if ($responsePayment['success'] && $responsePayment['code'] === 'PAYMENT_SUCCESS') {
            $trans_id = $responsePayment['data']['transactionId'];
            $amount = $responsePayment['data']['amount'];

            DB::table('payment')
                ->where('id', $pending->id)
                ->update(['status' => 'successful']);

            $payment_id = $pending->id;

            $code = genCode($pending->schedule_id, $pending->passenger_id, $class);

            DB::table('booked')->insert([
                'payment_id' => $payment_id,
                'schedule_id' => $pending->schedule_id,
                'cruise_id'=> $pending->cruise_id,
                'room_id'=> $pending->room_id,
                'user_id' => $pending->passenger_id,
                'code' => $code,
                'date' => $pending->date,
                'rooms_booked' => $pending->rooms_booked,
                'created_at' => now(),
                'updated_at' =>now()
            ]);

            $passenger=DB::table('passenger')->where('id', $pending->passenger_id)->first();
            // Optionally send email
            sendMail($passenger->name, "Payment Confirmation - E-Ticket System", "
                Your payment of Rs. {$pending->amount} has been successfully received.<br/><br/>
                <strong>Transaction Reference:</strong> {$trans_id}<br/>
                <strong>Ticket Code:</strong> {$code}<br/><br/>
                You can view or print your ticket in your account anytime.<br/>
                Thank you for choosing our service!");

            Session::put(['pay_success' => true, 'has_paid' => true]);
            Auth::guard('passenger')->loginUsingId($pending->passenger_id);

            return redirect()->route('ticket.paid')->with('success', 'Payment successful and ticket booked.');
        }

        return redirect()->route('passenger.booking')->with('error', 'Payment verification failed.');
    }

    
}
