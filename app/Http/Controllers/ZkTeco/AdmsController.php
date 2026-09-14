<?php

namespace App\Http\Controllers\ZkTeco;

use App\Http\Controllers\Controller;
use App\Models\ZkDevice;
use App\Models\ZkRawRequest;
use App\Services\ZkTeco\AttendanceParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdmsController extends Controller
{
    public function handshake(Request $request)
    {
        $serial = $request->query('SN');

        if (!$serial) {
            return response('ERROR: SN required', 400);
        }

        $this->storeRawRequest($request, $serial);

        $device = ZkDevice::firstOrCreate(
            ['serial_number' => $serial],
            [
                'device_ip' => $request->ip(),
                'is_active' => true,
            ]
        );

        $device->update([
            'device_ip' => $request->ip(),
            'push_version' => $request->query('pushver'),
            'last_seen_at' => now(),
            'metadata' => $request->query(),
        ]);

        return response(
            "GET OPTION FROM: {$serial}\r\n" .
            "Stamp=0\r\n" .
            "OpStamp=0\r\n" .
            "ErrorDelay=60\r\n" .
            "Delay=30\r\n" .
            "TransTimes=00:00;14:05\r\n" .
            "TransInterval=1\r\n" .
            "TransFlag=1111000000\r\n" .
            "TimeZone=+06:00\r\n" .
            "Realtime=1\r\n",
            200
        )->header('Content-Type', 'text/plain');
    }

    public function attendance(
        Request $request,
        AttendanceParser $parser
    ) {
        $serial = $request->query('SN');

        if (!$serial) {
            return response('ERROR: SN required', 400);
        }

        $this->storeRawRequest($request, $serial);

        $device = ZkDevice::where(
            'serial_number',
            $serial
        )->where(
            'is_active',
            true
        )->first();

        if (!$device) {
            return response('ERROR: Unknown Device', 403);
        }

        $device->update([
            'device_ip' => $request->ip(),
            'last_seen_at' => now(),
        ]);

        $table = strtoupper(
            $request->query('table', 'ATTLOG')
        );

        if ($table !== 'ATTLOG') {
            return response('OK', 200)
                ->header('Content-Type', 'text/plain');
        }

        $count = $parser->parseAndStore(
            $device,
            $request->getContent()
        );

        return response(
            "OK: {$count}",
            200
        )->header('Content-Type', 'text/plain');
    }

    public function getRequest(Request $request)
    {
        $serial = $request->query('SN');

        // TODO: Implement command polling through CommandService.
        // Return an empty/appropriate ADMS response when no command exists.

        return response('OK', 200)
            ->header('Content-Type', 'text/plain');
    }

    public function deviceCommand(Request $request)
    {
        $serial = $request->query('SN');

        $this->storeRawRequest($request, $serial);

        Log::info('ZKTeco device command response', [
            'serial' => $serial,
            'body' => $request->getContent(),
        ]);

        return response('OK', 200)
            ->header('Content-Type', 'text/plain');
    }

    private function storeRawRequest(
        Request $request,
        ?string $serial
    ): void {
        ZkRawRequest::create([
            'serial_number' => $serial,
            'method' => $request->method(),
            'uri' => $request->getRequestUri(),
            'query_params' => $request->query(),
            'headers' => json_encode(
                $request->headers->all()
            ),
            'body' => $request->getContent(),
            'remote_ip' => $request->ip(),
            'received_at' => now(),
        ]);
    }
}
