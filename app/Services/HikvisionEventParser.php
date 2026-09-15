<?php

namespace App\Services;

use Carbon\Carbon;
use RuntimeException;
use SimpleXMLElement;

class HikvisionEventParser
{
    public function parse(string $payload, ?string $contentType = null): array
    {
        $format = $this->detectFormat($payload, $contentType);

        if ($format === 'JSON') {
            $data = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Invalid Hikvision JSON payload.');
            }

            return [
                'format' => 'JSON',
                'data' => $this->normalize($data),
            ];
        }

        if ($format === 'XML') {
            libxml_use_internal_errors(true);

            $xml = simplexml_load_string($payload);

            if (!$xml instanceof SimpleXMLElement) {
                throw new RuntimeException('Invalid Hikvision XML payload.');
            }

            $data = json_decode(json_encode($xml), true);

            return [
                'format' => 'XML',
                'data' => $this->normalize($data),
            ];
        }

        throw new RuntimeException('Unknown Hikvision payload format.');
    }

    protected function detectFormat(string $payload, ?string $contentType): string
    {
        $contentType = strtolower($contentType ?? '');

        if (str_contains($contentType, 'json')) {
            return 'JSON';
        }

        if (str_contains($contentType, 'xml')) {
            return 'XML';
        }

        $trimmed = ltrim($payload);

        if (str_starts_with($trimmed, '<')) {
            return 'XML';
        }

        if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
            return 'JSON';
        }

        return 'UNKNOWN';
    }

    protected function normalize(array $data): array
    {
        if (isset($data['EventNotificationAlert'])) {
            $data = $data['EventNotificationAlert'];
        }

        $access = [];

        if (isset($data['AccessControllerEvent'])) {
            $access = $data['AccessControllerEvent'];
        }

        return [
            'event_type' => $this->value($data, 'eventType'),
            'event_state' => $this->value($data, 'eventState'),
            'event_time' => $this->date($this->value($data, 'dateTime')),
            'employee_no' => $this->value($access, 'employeeNoString'),
            'employee_name' => $this->value($access, 'name'),
            'card_no' => $this->value($access, 'cardNo'),
            'major_event_type' => $this->integer($access, 'majorEventType'),
            'sub_event_type' => $this->integer($access, 'subEventType'),
            'attendance_status' => $this->value($access, 'attendanceStatus'),
            'verify_mode' => $this->value($access, 'currentVerifyMode'),
            'serial_no' => $this->integer($access, 'serialNo'),
        ];
    }

    protected function value(array $data, string $key)
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        if (is_array($data[$key])) {
            return null;
        }

        return trim((string) $data[$key]);
    }

    protected function integer(array $data, string $key)
    {
        $value = $this->value($data, $key);

        return $value === null ? null : (int) $value;
    }

    protected function date($value)
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value)->timezone(config('app.timezone'));
        } catch (\Throwable $e) {
            return null;
        }
    }
}
