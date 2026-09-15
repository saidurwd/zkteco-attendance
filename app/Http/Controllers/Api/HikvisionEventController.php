<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HikvisionDevice;
use App\Models\HikvisionEvent;
use App\Jobs\ProcessHikvisionEvent;
use App\Services\HikvisionEventParser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class HikvisionEventController extends Controller
{
    public function receive(Request $request, HikvisionEventParser $parser)
    {
        $rawPayload = $request->getContent();

        if (empty($rawPayload)) {
            return response('Empty request body', Response::HTTP_BAD_REQUEST);
        }

        Log::channel('daily')->info('Hikvision event received', [
            'ip' => $request->ip(),
            'content_type' => $request->header('Content-Type'),
            'payload' => $rawPayload,
        ]);

        try {
            $result = $parser->parse($rawPayload, $request->header('Content-Type'));
            $data = $result['data'];

            $device = $this->findDevice($request, $data);

            if ($device && $data['serial_no'] !== null) {
                $existing = HikvisionEvent::query()
                    ->where('device_id', $device->id)
                    ->where('serial_no', $data['serial_no'])
                    ->first();

                if ($existing) {
                    return response('OK', 200);
                }
            }

            $event = HikvisionEvent::create([
                'device_id' => $device?->id,
                'event_type' => $data['event_type'],
                'event_state' => $data['event_state'],
                'event_time' => $data['event_time'],
                'employee_no' => $data['employee_no'],
                'employee_name' => $data['employee_name'],
                'card_no' => $data['card_no'],
                'major_event_type' => $data['major_event_type'],
                'sub_event_type' => $data['sub_event_type'],
                'attendance_status' => $data['attendance_status'],
                'verify_mode' => $data['verify_mode'],
                'serial_no' => $data['serial_no'],
                'raw_payload' => $rawPayload,
                'payload_format' => $result['format'],
                'processing_status' => 'PENDING',
                'received_at' => now(),
            ]);

            if ($device) {
                $device->update(['last_event_at' => now()]);
            }

            ProcessHikvisionEvent::dispatch($event->id);

            return response('OK', Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Hikvision webhook error', [
                'message' => $e->getMessage(),
                'payload' => $rawPayload,
            ]);

            return response('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function findDevice(Request $request, array $data): ?HikvisionDevice
    {
        return HikvisionDevice::query()
            ->where('ip_address', $request->ip())
            ->where('is_active', true)
            ->first();
    }
}
