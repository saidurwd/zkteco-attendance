<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zk_attendance_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')
                ->nullable()
                ->constrained('zk_devices')
                ->nullOnDelete();

            $table->string('serial_number', 100);
            $table->string('employee_pin', 50);
            $table->dateTime('attendance_time');

            $table->unsignedInteger('status')->nullable();
            $table->unsignedInteger('verify_type')->nullable();
            $table->unsignedInteger('work_code')->nullable();

            $table->string('reserved_1')->nullable();
            $table->string('reserved_2')->nullable();

            $table->text('raw_data')->nullable();
            $table->string('source', 50)->default('zkteco_push');

            $table->timestamps();

            $table->index(['serial_number', 'employee_pin', 'attendance_time'], 'zk_att_logs_sn_pin_time_idx');
            $table->index('attendance_time', 'zk_att_logs_time_idx');
            $table->index('employee_pin', 'zk_att_logs_pin_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zk_attendance_logs');
    }
};
