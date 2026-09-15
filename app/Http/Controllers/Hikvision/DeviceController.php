<?php

namespace App\Http\Controllers\Hikvision;

use App\Http\Controllers\Controller;
use App\Models\HikvisionDevice;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = HikvisionDevice::query()
            ->when($request->search, fn ($q, $search) => $q->where('device_name', 'like', "%{$search}%")
                ->orWhere('device_serial', 'like', "%{$search}%")
                ->orWhere('ip_address', 'like', "%{$search}%"))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('hikvision.devices.index', compact('devices'));
    }

    public function create()
    {
        return view('hikvision.devices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name' => 'required|string|max:100',
            'device_serial' => 'nullable|string|max:100|unique:hikvision_devices,device_serial',
            'device_model' => 'nullable|string|max:100',
            'ip_address' => 'nullable|string|max:45',
            'location' => 'nullable|string|max:150',
            'username' => 'nullable|string|max:100',
            'password_encrypted' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        HikvisionDevice::create($validated);

        return redirect()->route('hikvision.devices.index')
            ->with('success', 'Hikvision device created successfully.');
    }

    public function edit(HikvisionDevice $device)
    {
        return view('hikvision.devices.edit', compact('device'));
    }

    public function update(Request $request, HikvisionDevice $device)
    {
        $validated = $request->validate([
            'device_name' => 'required|string|max:100',
            'device_serial' => 'nullable|string|max:100|unique:hikvision_devices,device_serial,' . $device->id,
            'device_model' => 'nullable|string|max:100',
            'ip_address' => 'nullable|string|max:45',
            'location' => 'nullable|string|max:150',
            'username' => 'nullable|string|max:100',
            'password_encrypted' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $device->update($validated);

        return redirect()->route('hikvision.devices.index')
            ->with('success', 'Hikvision device updated successfully.');
    }

    public function destroy(HikvisionDevice $device)
    {
        $device->delete();

        return redirect()->route('hikvision.devices.index')
            ->with('success', 'Hikvision device deleted successfully.');
    }
}
