<?php

namespace App\Http\Controllers\ZkTeco\Api;

use App\Http\Controllers\Controller;
use App\Models\ZkDevice;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = ZkDevice::query()
            ->when($request->search, fn ($q, $search) => $q->where('serial_number', 'like', "%{$search}%")
                ->orWhere('device_name', 'like', "%{$search}%"))
            ->orderByDesc('last_seen_at')
            ->paginate(20)
            ->withQueryString();

        return response()->json($devices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|string|max:100|unique:zk_devices,serial_number',
            'device_name' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:100',
            'firmware_version' => 'nullable|string|max:100',
            'site_code' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $device = ZkDevice::create($validated);

        return response()->json($device, 201);
    }

    public function show(ZkDevice $device)
    {
        return response()->json($device);
    }

    public function update(Request $request, ZkDevice $device)
    {
        $validated = $request->validate([
            'device_name' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:100',
            'firmware_version' => 'nullable|string|max:100',
            'site_code' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $device->update($validated);

        return response()->json($device);
    }

    public function destroy(ZkDevice $device)
    {
        $device->delete();

        return response()->json(null, 204);
    }
}
