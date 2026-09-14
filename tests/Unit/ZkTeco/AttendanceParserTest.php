<?php

namespace Tests\Unit\ZkTeco;

use App\Jobs\ZkTeco\ProcessAttendance;
use App\Models\ZkAttendanceLog;
use App\Models\ZkDevice;
use App\Services\ZkTeco\AttendanceParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AttendanceParserTest extends TestCase
{
    use RefreshDatabase;

    public function test_parse_empty_body_returns_zero(): void
    {
        $device = ZkDevice::create(['serial_number' => 'DEV1', 'is_active' => true]);

        $parser = new AttendanceParser();
        $count = $parser->parseAndStore($device, '');

        $this->assertEquals(0, $count);
    }

    public function test_parse_single_record_creates_attendance_log(): void
    {
        $device = ZkDevice::create(['serial_number' => 'DEV2', 'is_active' => true]);

        $parser = new AttendanceParser();
        $count = $parser->parseAndStore($device, "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0");

        $this->assertEquals(1, $count);
        $this->assertDatabaseHas('zk_attendance_logs', [
            'device_id' => $device->id,
            'serial_number' => $device->serial_number,
            'employee_pin' => '10001',
            'status' => 0,
            'verify_type' => 15,
            'work_code' => 0,
        ]);
    }

    public function test_parse_multiple_records_creates_multiple_logs(): void
    {
        $device = ZkDevice::create(['serial_number' => 'DEV3', 'is_active' => true]);

        $body = "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0\n10002\t2026-09-14 08:35:00\t0\t15\t0\t0\t0";

        $parser = new AttendanceParser();
        $count = $parser->parseAndStore($device, $body);

        $this->assertEquals(2, $count);
        $this->assertEquals(2, ZkAttendanceLog::count());
    }

    public function test_parse_skips_malformed_rows(): void
    {
        $device = ZkDevice::create(['serial_number' => 'DEV4', 'is_active' => true]);

        $body = "BADLINE\n10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0";

        $parser = new AttendanceParser();
        $count = $parser->parseAndStore($device, $body);

        $this->assertEquals(1, $count);
    }

    public function test_parse_skips_invalid_timestamp(): void
    {
        $device = ZkDevice::create(['serial_number' => 'DEV5', 'is_active' => true]);

        $body = "10001\tnot-a-date\t0\t15\t0\t0\t0";

        $parser = new AttendanceParser();
        $count = $parser->parseAndStore($device, $body);

        $this->assertEquals(0, $count);
    }

    public function test_parse_does_not_create_duplicates(): void
    {
        $device = ZkDevice::create(['serial_number' => 'DEV6', 'is_active' => true]);

        $body = "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0";

        $parser = new AttendanceParser();
        $parser->parseAndStore($device, $body);
        $count = $parser->parseAndStore($device, $body);

        $this->assertEquals(0, $count);
        $this->assertEquals(1, ZkAttendanceLog::count());
    }

    public function test_parse_supports_crlf_line_endings(): void
    {
        $device = ZkDevice::create(['serial_number' => 'DEV7', 'is_active' => true]);

        $body = "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0\r\n10002\t2026-09-14 08:35:00\t0\t15\t0\t0\t0";

        $parser = new AttendanceParser();
        $count = $parser->parseAndStore($device, $body);

        $this->assertEquals(2, $count);
    }

    public function test_parse_dispatches_job_for_new_records(): void
    {
        Queue::fake();

        $device = ZkDevice::create(['serial_number' => 'DEV8', 'is_active' => true]);

        $body = "10001\t2026-09-14 08:30:00\t0\t15\t0\t0\t0";

        $parser = new AttendanceParser();
        $parser->parseAndStore($device, $body);

        Queue::assertPushed(ProcessAttendance::class, 1);
    }
}
