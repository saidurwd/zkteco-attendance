<?php

namespace App\Http\Controllers\ZkTeco;

use App\Http\Controllers\Controller;
use App\Models\ZkDevice;
use App\Models\ZkEmployeeMapping;
use Illuminate\Http\Request;

class MappingController extends Controller
{
    public function index(Request $request, ZkDevice $device)
    {
        $mappings = $device->employeeMappings()
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('zkteco.mappings.index', compact('device', 'mappings'));
    }

    public function create(ZkDevice $device)
    {
        return view('zkteco.mappings.create', compact('device'));
    }

    public function store(Request $request, ZkDevice $device)
    {
        $validated = $request->validate([
            'device_pin' => 'required|string|max:50',
            'employee_code' => 'required|string|max:100',
        ]);

        ZkEmployeeMapping::create([
            'device_id' => $device->id,
            'device_pin' => $validated['device_pin'],
            'employee_code' => $validated['employee_code'],
            'is_active' => true,
        ]);

        return redirect()->route('zkteco.devices.mappings.index', $device)
            ->with('success', 'Mapping created successfully.');
    }

    public function edit(ZkDevice $device, ZkEmployeeMapping $mapping)
    {
        return view('zkteco.mappings.edit', compact('device', 'mapping'));
    }

    public function update(Request $request, ZkDevice $device, ZkEmployeeMapping $mapping)
    {
        $validated = $request->validate([
            'employee_code' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $mapping->update($validated);

        return redirect()->route('zkteco.devices.mappings.index', $device)
            ->with('success', 'Mapping updated successfully.');
    }

    public function destroy(ZkDevice $device, ZkEmployeeMapping $mapping)
    {
        $mapping->delete();

        return redirect()->route('zkteco.devices.mappings.index', $device)
            ->with('success', 'Mapping deleted successfully.');
    }
}
