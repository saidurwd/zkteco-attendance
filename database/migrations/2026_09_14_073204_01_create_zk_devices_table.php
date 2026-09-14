<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zk_devices', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 100)->unique();
            $table->string('device_name')->nullable();
            $table->string('device_ip', 45)->nullable();
            $table->string('model')->nullable();
            $table->string('firmware_version')->nullable();
            $table->string('push_version')->nullable();
            $table->string('site_code')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_seen_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('device_ip');
            $table->index('last_seen_at');
            $table->index('site_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zk_devices');
    }
};
