<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Cruise;
use App\Models\Route;
use App\Models\Booked;
use TCPDF;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['cruise', 'route'])->get();
        $cruise = Cruise::all();
        $routes = Route::all();

    
        return view('admin.pages.reports', compact('schedules','cruise', 'routes'));
    }

    public function downloadReport($id)
    {
        $bookings = DB::table('booked')
            ->join('schedule', 'schedule.id', '=', 'booked.schedule_id')
            ->join('passenger', 'passenger.id', '=', 'booked.user_id')
            ->join('rooms','rooms.id','=','booked.room_id')
            ->where('booked.schedule_id', $id)
            ->orderBy('type')
            ->select('schedule.date', 'schedule.time', 'schedule.cruise_id as train', 'schedule.route_id as route', 'rooms.room_number', 'passenger.name as fullname', 'booked.code', 'rooms.type')
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->back()->with('error', 'No passengers for this schedule.');
        }
    
        $scheduleName = getRouteFromSchedule($id);
        $train = getTrainName($bookings[0]->train);
        $date = $bookings[0]->date;
        $time = formatTime($bookings[0]->time);
    
        $output = '<style>
            .a { text-align:left; width: 10%; }
            .b { width: 20% }
            .c { width: 30%; }
            table { border: 1px solid green; border-collapse: collapse; width: 100%; white-space: nowrap; }
            th { font-weight: bold; }
            .shrink { white-space: nowrap; width: 40%; }
            .expand { width: 99% }
        </style>';
    
        $sn = 0;
        foreach ($bookings as $row) {
            $sn++;
            $output .= "<tr>
                <td class='a'>{$sn}</td>
                <td class='c'>" . substr(ucwords(strtolower($row->fullname)), 0, 15) . "</td>
                <td class='shrink'>{$row->code} (" . ucwords($row->type) . ")</td>
                <td class='b'>" . strtoupper($row->room_number) . "</td>
            </tr>";
        }
    
        $table = "<table><tr>
            <th class='a'>SN</th>
            <th class='c'>Full Name</th>
            <th class='shrink'>Code/Class</th>
            <th class='b'>Room No</th>
        </tr>$output</table>";
    
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 10);

        $html = "<h4 style='text-align:center'>
            <img src='" . public_path('images/trainlg.png') . "' width='80' height='80'/><br/>
            ONLINE TICKET RESERVATION SYSTEM<br/>
            LIST OF BOOKINGS FOR $date ($time)
        </h4>
        <div style='text-align:right; font-family:courier;font-weight:bold'>
            <font size='+1'>Cruise $train ($sn Passengers): $scheduleName</font>
        </div>$table";
    
        $pdf->writeHTML($html, true, false, true, false, '');
        return response($pdf->Output('train-bookings.pdf', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="train-bookings.pdf"');
    }
}
