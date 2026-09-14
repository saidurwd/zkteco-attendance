<?php

namespace App\Http\Controllers\ZkTeco;

use App\Http\Controllers\Controller;
use App\Models\ZkDevice;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = ZkDevice::query()
            ->when($request->search, fn ($q, $search) => $q->where('serial_number', 'like', "%{$search}%")
                ->orWhere('device_name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%"))
            ->orderByDesc('last_seen_at')
            ->paginate(20)
            ->withQueryString();

        return view('zkteco.devices.index', compact('devices'));
    }

    public function create()
    {
        return view('zkteco.devices.create');
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

        ZkDevice::create($validated);

        return redirect()->route('zkteco.devices.index')
            ->with('success', 'Device created successfully.');
    }

    public function edit(ZkDevice $device)
    {
        return view('zkteco.devices.edit', compact('device'));
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

        return redirect()->route('zkteco.devices.index')
            ->with('success', 'Device updated successfully.');
    }

    public function toggleActive(ZkDevice $device)
    {
        $device->update([
            'is_active' => !$device->is_active,
        ]);

        return back()->with('success', 'Device status updated.');
    }

    public function destroy(ZkDevice $device)
    {
        $device->delete();

        return redirect()->route('zkteco.devices.index')
            ->with('success', 'Device deleted successfully.');
    }
}
