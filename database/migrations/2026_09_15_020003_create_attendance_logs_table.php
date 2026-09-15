<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('employee_no', 100);
            $table->foreignId('device_id')->nullable()->constrained('hikvision_devices')->nullOnDelete();
            $table->dateTime('attendance_time');
            $table->string('attendance_type', 30)->nullable();
            $table->string('verify_mode', 50)->nullable();
            $table->string('source', 30)->default('HIKVISION');
            $table->foreignId('hikvision_event_id')->nullable()->unique()->constrained('hikvision_events')->nullOnDelete();
            $table->timestamps();
            $table->index(['employee_no', 'attendance_time']);
            $table->index(['device_id', 'attendance_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
