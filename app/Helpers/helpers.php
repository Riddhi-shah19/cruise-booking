<?php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Passenger;
use App\Models\Schedule;
use App\Mail\NotificationMail;
use BaconQrCode\Encoder\Encoder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


function getRoutePath($id)
{
    $route = DB::table('route')->where('id', $id)->first();
    return $route ? $route->start . ' to ' . $route->stop : 'Route Not Found';
}

function getRouteFromSchedule($id)
{
    $route = DB::table('schedule')->where('id', $id)->value('route_id');
    return getRoutePath($route);
}

function getTrainName($id)
{
    $train = DB::table('cruise')->where('id', $id)->value('name');
    return $train ?? 'Train Not Found';
}

function getTotalBookByType($id)
{
    $secondBooked = DB::table('booked')
    ->join('rooms', 'booked.room_id', '=', 'rooms.id') // Join rooms to get type
    ->where('booked.schedule_id', $id)
    ->where('rooms.type', 'luxury') // Check for luxury type in rooms table
    ->sum('booked.rooms_booked'); // Sum booked rooms for luxury type

    $firstBooked = DB::table('booked')
        ->join('rooms', 'booked.room_id', '=', 'rooms.id') // Join rooms to get type
        ->where('booked.schedule_id', $id)
        ->where('rooms.type', 'general') // Check for general type in rooms table
        ->sum('booked.rooms_booked'); // Sum booked rooms for general type

    $seats = DB::table('schedule')
        ->join('cruise', 'schedule.cruise_id', '=', 'cruise.id')
        ->where('schedule.id', $id)
        ->select('cruise.general_rooms as first', 'cruise.luxury_rooms as second') // Use cruise table for seat capacity
        ->first();

    return [
        'first' => intval($seats->first ?? 0),  // General seats (first)
        'second' => intval($seats->second ?? 0),  // Luxury seats (second)
        'first_booked' => intval($firstBooked),  // Total booked general rooms
        'second_booked' => intval($secondBooked),  // Total booked luxury rooms
    ];

}

function formatTime($time)
{
    return Carbon::createFromFormat('H:i', $time)->format('g:i A');
}

function getIndividualName($id)
{
    $passenger = Passenger::find($id);
    return $passenger ? $passenger->name : null;
}

function genCode($scheduleId, $userId, $class)
{
    $totalBooked = DB::table('booked')
        ->where('schedule_id', $scheduleId)
        ->sum('rooms_booked');

    $no = $totalBooked ? $totalBooked + 1 : 1;

    // Determine the zero-padding for the schedule ID
    $number = match (strlen((string)$scheduleId)) {
        1 => '00',
        2 => '0',
        default => '0',
    };

    $code = date('Y') . "/{$number}{$scheduleId}/{$no}" . mt_rand(1, 882);

    return $code;
}

function sendMail($to, $subject, $msg)
{
    $toName = $to;
    $title = "E-TICKET SYSTEM"; 
    try {
        Mail::to($to)->send(new NotificationMail($toName, $msg, $title));
        return 1;
    } catch (\Exception $e) {
        Log::error("Mail failed: " . $e->getMessage());
        return 0;
    }
}

function isScheduleActive($id)
{
    $today = Carbon::now()->format('Y-m-d');
    $timeNow = Carbon::now()->format('H:i:s');

    // Get the schedule with date >= today
    $schedule = Schedule::where('id', $id)
        ->whereRaw("STR_TO_DATE(`date`, '%Y-%m-%d') >= STR_TO_DATE(?, '%Y-%m-%d')", [$today])
        ->first();
// dd($schedule);
    if ($schedule) {
        if ($schedule->date === $today) {
            if (strtotime($schedule->time) <= strtotime($timeNow)) {
                return false;
            }
        }
        return true;
    }

    return false;
}


function generateQR($id, $data)
{
    $fileName = intval($id) . ".png";
    $filePath = public_path('qrcodes/' . $fileName);

    // Ensure directory exists
    if (!file_exists(public_path('qrcodes'))) {
        mkdir(public_path('qrcodes'), 0777, true);
    }

    $writer = new PngWriter();

    $qrCode = new QrCode(
        data: $data,
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel:  ErrorCorrectionLevel::Low,
        size: 300,
        margin: 10,
        roundBlockSizeMode:  RoundBlockSizeMode::Margin,
        foregroundColor: new Color(0, 0, 0),
        backgroundColor: new Color(255, 255, 255)
    );

    
    $result = $writer->write($qrCode); // , $logo, $label if needed

    // Save PNG
    $result->saveToFile($filePath);

   
    // Modify dark pixel colors using GD
    $im = imagecreatefrompng($filePath);
    for ($x = 0; $x < imagesx($im); ++$x) {
        for ($y = 0; $y < imagesy($im); ++$y) {
            $index = imagecolorat($im, $x, $y);
            $c = imagecolorsforindex($im, $index);
            if (($c['red'] < 100) && ($c['green'] < 100) && ($c['blue'] < 100)) {
                $colorB = imagecolorallocatealpha($im, 0x12, 0x2E, 0x31, $c['alpha']);
                imagesetpixel($im, $x, $y, $colorB);
            }
        }
    }
    imagepng($im, $filePath);
    imagedestroy($im);

    // Convert to base64 if needed
    $type = pathinfo($filePath, PATHINFO_EXTENSION);
    $imgData = file_get_contents($filePath);
    $imgbase64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);

    chmod($filePath, 0777);

    return $imgbase64;
}

function sum($scheduleId, $type = null)
{
    $query = DB::table('payment')
        ->join('booked', function ($join) {
            $join->on('booked.payment_id', '=', 'payment.id')
                 ->on('booked.schedule_id', '=', 'payment.schedule_id');
        })
        ->join('rooms','rooms.id','=','payment.room_id')
        ->where('payment.schedule_id', $scheduleId);

    if ($type !== null) {
        $query->where('rooms.type', $type);
    }

    $amount = $query->sum('amount');

    return $amount ?? 0;
}



