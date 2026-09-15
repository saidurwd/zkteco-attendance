<?php

namespace App\Http\Controllers\Hikvision;

use App\Http\Controllers\Controller;
use App\Models\HikvisionDevice;
use App\Models\HikvisionEvent;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = HikvisionEvent::query()
            ->with('device')
            ->when($request->filled('device_id'), fn ($q) => $q->where('device_id', $request->device_id))
            ->when($request->filled('employee_no'), fn ($q) => $q->where('employee_no', 'like', "%{$request->employee_no}%"))
            ->when($request->filled('processing_status'), fn ($q) => $q->where('processing_status', $request->processing_status))
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('event_time', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('event_time', '<=', $request->end_date))
            ->orderByDesc('event_time')
            ->paginate(50)
            ->withQueryString();

        $devices = HikvisionDevice::orderBy('device_name')->get(['id', 'device_name', 'device_serial']);

        return view('hikvision.events.index', compact('query', 'devices'));
    }

    public function show(HikvisionEvent $event)
    {
        $event->load('device');

        return view('hikvision.events.show', compact('event'));
    }

    public function attendance(Request $request)
    {
        $tz = Config::get('app.timezone', 'UTC');

        $query = DB::table('attendance_logs as al')
            ->selectRaw("
                al.device_id,
                d.device_name,
                d.device_serial,
                al.employee_no,
                DATE(al.attendance_time) AS attendance_date,
                MIN(al.attendance_time) AS clock_in,
                MAX(al.attendance_time) AS clock_out,
                COUNT(al.id) AS total_entries,
                TIMEDIFF(MAX(al.attendance_time), MIN(al.attendance_time)) AS total_hours
            ")
            ->leftJoin('hikvision_devices as d', 'd.id', '=', 'al.device_id')
            ->groupBy('al.device_id', 'd.device_name', 'd.device_serial', 'al.employee_no',
                      DB::raw('DATE(al.attendance_time)'))
            ->orderByDesc('attendance_date')
            ->orderBy('al.employee_no');

        if ($request->filled('start_date')) {
            $query->whereDate('al.attendance_time', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('al.attendance_time', '<=', $request->end_date);
        }
        if ($request->filled('employee_no')) {
            $query->where('al.employee_no', 'like', "%{$request->employee_no}%");
        }
        if ($request->filled('device_id')) {
            $query->where('al.device_id', $request->device_id);
        }

        $reports = $query->paginate(50)->withQueryString();

        $devices = HikvisionDevice::orderBy('device_name')->get(['id', 'device_name', 'device_serial']);

        return view('hikvision.reports.attendance', compact('reports', 'devices', 'tz'));
    }
}
