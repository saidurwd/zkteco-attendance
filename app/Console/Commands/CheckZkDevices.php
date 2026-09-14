<?php

namespace App\Console\Commands;

use App\Models\ZkDevice;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('zkteco:check-devices')]
#[Description('Check ZKTeco device online/offline status')]
class CheckZkDevices extends Command
{
    protected $signature = 'zkteco:check-devices';
    protected $description = 'Check ZKTeco device online/offline status';

    public function handle(): void
    {
        $offlineThreshold = now()->subMinutes(
            (int) env('ZKTECO_DEVICE_OFFLINE_MINUTES', 5)
        );

        $devices = ZkDevice::where('is_active', true)
            ->where(function ($query) use ($offlineThreshold) {
                $query->whereNull('last_seen_at')
                    ->orWhere('last_seen_at', '<', $offlineThreshold);
            })
            ->get();

        $offlineCount = 0;

        foreach ($devices as $device) {
            $offlineCount++;

            Log::warning('ZKTeco device offline', [
                'serial' => $device->serial_number,
                'last_seen_at' => $device->last_seen_at?->format('Y-m-d H:i:s'),
                'device_ip' => $device->device_ip,
            ]);
        }

        $totalActive = ZkDevice::where('is_active', true)->count();

        $this->info("Device status check complete.");
        $this->info("Total active devices: {$totalActive}");
        $this->info("Offline devices: {$offlineCount}");
    }
}
