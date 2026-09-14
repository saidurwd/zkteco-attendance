<?php

namespace App\Http\Controllers\ZkTeco;

use App\Http\Controllers\Controller;
use App\Models\ZkAttendanceLog;
use Illuminate\Http\Request;

class AttendanceLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = ZkAttendanceLog::with('device')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('employee_pin', 'like', "%{$search}%");
            });
        }

        $attendanceLogs = $query->paginate(20);

        return view('zkteco.attendance-logs.index', compact('attendanceLogs'));
    }

    public function create()
    {
        return view('zkteco.attendance-logs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => ['required', 'string', 'max:100'],
            'employee_pin' => ['required', 'string', 'max:50'],
            'attendance_time' => ['required', 'date'],
            'status' => ['nullable', 'integer'],
            'verify_type' => ['nullable', 'integer'],
            'work_code' => ['nullable', 'integer'],
            'reserved_1' => ['nullable', 'string', 'max:255'],
            'reserved_2' => ['nullable', 'string', 'max:255'],
            'raw_data' => ['nullable', 'text'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        ZkAttendanceLog::create($validated);

        return redirect()->route('zkteco.attendance-logs.index')->with('status', 'Attendance log created successfully.');
    }

    public function edit(ZkAttendanceLog $attendanceLog)
    {
        return view('zkteco.attendance-logs.edit', compact('attendanceLog'));
    }

    public function update(Request $request, ZkAttendanceLog $attendanceLog)
    {
        $validated = $request->validate([
            'serial_number' => ['required', 'string', 'max:100'],
            'employee_pin' => ['required', 'string', 'max:50'],
            'attendance_time' => ['required', 'date'],
            'status' => ['nullable', 'integer'],
            'verify_type' => ['nullable', 'integer'],
            'work_code' => ['nullable', 'integer'],
            'reserved_1' => ['nullable', 'string', 'max:255'],
            'reserved_2' => ['nullable', 'string', 'max:255'],
            'raw_data' => ['nullable', 'text'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        $attendanceLog->update($validated);

        return redirect()->route('zkteco.attendance-logs.index')->with('status', 'Attendance log updated successfully.');
    }

    public function destroy(ZkAttendanceLog $attendanceLog)
    {
        $attendanceLog->delete();

        return back()->with('status', 'Attendance log deleted successfully.');
    }
}
