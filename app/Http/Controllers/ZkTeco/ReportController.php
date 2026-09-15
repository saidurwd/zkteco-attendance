<?php

namespace App\Http\Controllers\ZkTeco;

use App\Http\Controllers\Controller;
use App\Models\ZkEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function attendance(Request $request)
    {
        $tz = Config::get('app.timezone', 'UTC');

        $tzOffset = Carbon::create(2026, 1, 1, 0, 0, 0, $tz)->format('P');

        $localLogs = DB::table(DB::raw("(
            SELECT 
                al.device_id,
                al.serial_number,
                al.employee_pin,
                DATE(CONVERT_TZ(al.attendance_time, 'UTC', '{$tzOffset}')) AS attendance_date,
                CONVERT_TZ(al.attendance_time, 'UTC', '{$tzOffset}') AS attendance_time_local
            FROM zk_attendance_logs al
        ) as al"));

        $query = $localLogs
            ->selectRaw("
                al.device_id,
                al.serial_number,
                e.id AS employee_id,
                e.employee_id AS employee_code,
                e.name,
                e.department,
                e.position,
                e.site_code,
                al.attendance_date,
                MIN(al.attendance_time_local) AS clock_in,
                MAX(al.attendance_time_local) AS clock_out,
                COUNT(*) AS total_entries,
                TIMEDIFF(
                    MAX(al.attendance_time_local),
                    MIN(al.attendance_time_local)
                ) AS total_hours
            ")
            ->leftJoin('zk_employees as e', function ($join) {
                $join->on('e.employee_id', '=', 'al.employee_pin')
                     ->where('e.is_active', '=', 1);
            })
            ->groupBy('al.device_id', 'al.serial_number', 'e.id', 'e.employee_id',
                      'e.name', 'e.department', 'e.position', 'e.site_code',
                      'al.attendance_date')
            ->orderByDesc('al.attendance_date')
            ->orderBy('e.name');

        if ($request->filled('start_date')) {
            $query->whereDate('al.attendance_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('al.attendance_date', '<=', $request->end_date);
        }
        if ($request->filled('employee_id')) {
            $query->where('e.id', $request->employee_id);
        }
        if ($request->filled('department')) {
            $query->where('e.department', $request->department);
        }

        $reports = $query->paginate(50)->withQueryString();

        $employees = ZkEmployee::where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'employee_id', 'name', 'department']);

        $departments = ZkEmployee::where('is_active', 1)
            ->distinct()
            ->orderBy('department')
            ->pluck('department')
            ->filter()
            ->values();

        return view('zkteco.reports.attendance', compact('reports', 'employees', 'departments', 'tz'));
    }
}
