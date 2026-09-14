<?php

namespace App\Http\Controllers\ZkTeco\Api;

use App\Http\Controllers\Controller;
use App\Models\ZkAttendanceLog;
use App\Models\ZkDevice;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request, ZkDevice $device)
    {
        $attendance = ZkAttendanceLog::query()
            ->where('device_id', $device->id)
            ->when($request->from, fn ($q, $from) => $q->where('attendance_time', '>=', $from))
            ->when($request->to, fn ($q, $to) => $q->where('attendance_time', '<=', $to))
            ->orderByDesc('attendance_time')
            ->paginate(50)
            ->withQueryString();

        return response()->json($attendance);
    }

    public function show(ZkDevice $device, ZkAttendanceLog $attendance)
    {
        if ($attendance->device_id !== $device->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($attendance);
    }
}
