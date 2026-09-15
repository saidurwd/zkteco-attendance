<?php

namespace App\Http\Controllers\ZkTeco;

use App\Http\Controllers\Controller;
use App\Models\ZkEmployee;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function attendance(Request $request)
    {
        $tz = Config::get('app.timezone', 'UTC');

        $tzOffsetSeconds = (new DateTimeZone($tz))->getOffset(new DateTime());
        $tzOffsetHours = $tzOffsetSeconds / 3600;

        $tzExpr = "TIMESTAMPADD(HOUR, {$tzOffsetHours}, al.attendance_time)";

        $query = DB::table('zk_attendance_logs as al')
            ->selectRaw("
                al.device_id,
                al.serial_number,
                e.id AS employee_id,
                e.employee_id AS employee_code,
                e.name,
                e.department,
                e.position,
                e.site_code,
                DATE({$tzExpr}) AS attendance_date,
                MIN({$tzExpr}) AS clock_in,
                MAX({$tzExpr}) AS clock_out,
                COUNT(al.id) AS total_entries,
                TIMEDIFF(MAX({$tzExpr}), MIN({$tzExpr})) AS total_hours
            ")
            ->leftJoin('zk_employees as e', function ($join) {
                $join->on('e.employee_id', '=', 'al.employee_pin')
                     ->where('e.is_active', '=', 1);
            })
            ->groupBy('al.device_id', 'al.serial_number', 'e.id', 'e.employee_id',
                      'e.name', 'e.department', 'e.position', 'e.site_code',
                      DB::raw("DATE({$tzExpr})"))
            ->orderByDesc('attendance_date')
            ->orderBy('e.name');

        if ($request->filled('start_date')) {
            $query->whereDate(DB::raw($tzExpr), '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate(DB::raw($tzExpr), '<=', $request->end_date);
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
