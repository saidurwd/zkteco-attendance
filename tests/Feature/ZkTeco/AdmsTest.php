<?php

namespace Tests\Feature\ZkTeco;

use App\Models\ZkDevice;
use App\Models\ZkRawRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_handshake_returns_option_text_for_valid_serial(): void
    {
        $response = $this->get('/iclock/cdata?SN=TEST123&options=all&pushver=3.1.2');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('GET OPTION FROM: TEST123');
        $response->assertSee('Realtime=1');
    }

    public function test_handshake_creates_device_if_not_exists(): void
    {
        $this->get('/iclock/cdata?SN=NEWDEVICE&options=all');

        $this->assertDatabaseHas('zk_devices', [
            'serial_number' => 'NEWDEVICE',
            'is_active' => true,
        ]);
    }

    public function test_handshake_updates_existing_device(): void
    {
        $device = ZkDevice::create([
            'serial_number' => 'EXISTING',
            'is_active' => true,
        ]);

        $this->get('/iclock/cdata?SN=EXISTING&options=all&pushver=2.0');

        $device->refresh();
        $this->assertEquals('2.0', $device->push_version);
    }

    public function test_handshake_stores_raw_request(): void
    {
        $this->get('/iclock/cdata?SN=TEST123&options=all');

        $this->assertDatabaseHas('zk_raw_requests', [
            'serial_number' => 'TEST123',
            'method' => 'GET',
        ]);
    }

    public function test_handshake_returns_error_without_serial(): void
    {
        $response = $this->get('/iclock/cdata?options=all');

        $response->assertStatus(400);
        $response->assertSee('ERROR: SN required');
    }

    public function test_attendance_accepts_valid_payload_for_active_device(): void
    {
        ZkDevice::create([
            'serial_number' => 'ACTIVE',
            'is_active' => true,
        ]);

        $body = "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0\n10002\t2026-09-14 08:35:00\t0\t15\t0\t0\t0";

        $response = $this->call('POST', '/iclock/cdata?SN=ACTIVE&table=ATTLOG', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $body);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('OK: 2');
    }

    public function test_attendance_rejects_unknown_device(): void
    {
        $body = "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0";

        $response = $this->call('POST', '/iclock/cdata?SN=UNKNOWN&table=ATTLOG', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $body);

        $response->assertStatus(403);
        $response->assertSee('ERROR: Unknown Device');
    }

    public function test_attendance_stores_records_in_database(): void
    {
        ZkDevice::create([
            'serial_number' => 'STORE',
            'is_active' => true,
        ]);

        $body = "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0";

        $this->call('POST', '/iclock/cdata?SN=STORE&table=ATTLOG', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $body);

        $this->assertDatabaseHas('zk_attendance_logs', [
            'serial_number' => 'STORE',
            'employee_pin' => '10001',
        ]);
    }

    public function test_attendance_handles_malformed_rows_gracefully(): void
    {
        ZkDevice::create([
            'serial_number' => 'MALFORMED',
            'is_active' => true,
        ]);

        $body = "BADLINE\n10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0";

        $response = $this->call('POST', '/iclock/cdata?SN=MALFORMED&table=ATTLOG', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $body);

        $response->assertStatus(200);
        $response->assertSee('OK: 1');
    }

    public function test_attendance_prevents_duplicate_records(): void
    {
        ZkDevice::create([
            'serial_number' => 'DUP',
            'is_active' => true,
        ]);

        $body = "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0";

        $this->call('POST', '/iclock/cdata?SN=DUP&table=ATTLOG', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $body);

        $this->call('POST', '/iclock/cdata?SN=DUP&table=ATTLOG', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], $body);

        $this->assertEquals(1, \App\Models\ZkAttendanceLog::count());
    }

    public function test_getrequest_returns_ok(): void
    {
        $response = $this->get('/iclock/getrequest?SN=TEST123');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('OK');
    }

    public function test_devicecmd_stores_raw_request_and_returns_ok(): void
    {
        $response = $this->call('POST', '/iclock/devicecmd?SN=TEST123', [], [], [], [
            'CONTENT_TYPE' => 'text/plain',
        ], 'CMD RESPONSE');

        $response->assertStatus(200);
        $response->assertSee('OK');

        $this->assertDatabaseHas('zk_raw_requests', [
            'serial_number' => 'TEST123',
            'method' => 'POST',
        ]);
    }
}
