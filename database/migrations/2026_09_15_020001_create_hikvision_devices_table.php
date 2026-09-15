<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hikvision_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_name', 100);
            $table->string('device_serial', 100)->nullable()->unique();
            $table->string('device_model', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('location', 150)->nullable();
            $table->string('username', 100)->nullable();
            $table->text('password_encrypted')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('last_event_at')->nullable();
            $table->timestamps();
            $table->index('is_active');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hikvision_devices');
    }
};
