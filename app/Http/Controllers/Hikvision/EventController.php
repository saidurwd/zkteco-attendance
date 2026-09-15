<?php

namespace App\Http\Controllers\Hikvision;

use App\Http\Controllers\Controller;
use App\Models\HikvisionEvent;
use App\Models\HikvisionDevice;
use Illuminate\Http\Request;

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
}
