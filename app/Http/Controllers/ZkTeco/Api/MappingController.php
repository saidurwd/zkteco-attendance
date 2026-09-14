<?php

namespace App\Http\Controllers\ZkTeco\Api;

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

        return response()->json($mappings);
    }

    public function store(Request $request, ZkDevice $device)
    {
        $validated = $request->validate([
            'device_pin' => 'required|string|max:50',
            'employee_code' => 'required|string|max:100',
        ]);

        $mapping = ZkEmployeeMapping::create([
            'device_id' => $device->id,
            'device_pin' => $validated['device_pin'],
            'employee_code' => $validated['employee_code'],
            'is_active' => true,
        ]);

        return response()->json($mapping, 201);
    }

    public function update(Request $request, ZkDevice $device, ZkEmployeeMapping $mapping)
    {
        if ($mapping->device_id !== $device->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'employee_code' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $mapping->update($validated);

        return response()->json($mapping);
    }

    public function destroy(ZkDevice $device, ZkEmployeeMapping $mapping)
    {
        if ($mapping->device_id !== $device->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $mapping->delete();

        return response()->json(null, 204);
    }
}
