<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hikvision_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->nullable()->constrained('hikvision_devices')->nullOnDelete();
            $table->string('event_type', 100)->nullable();
            $table->string('event_state', 50)->nullable();
            $table->dateTime('event_time')->nullable();
            $table->string('employee_no', 100)->nullable();
            $table->string('employee_name', 150)->nullable();
            $table->string('card_no', 100)->nullable();
            $table->integer('major_event_type')->nullable();
            $table->integer('sub_event_type')->nullable();
            $table->string('attendance_status', 50)->nullable();
            $table->string('verify_mode', 50)->nullable();
            $table->bigInteger('serial_no')->nullable();
            $table->longText('raw_payload');
            $table->enum('payload_format', ['XML', 'JSON', 'UNKNOWN'])->default('UNKNOWN');
            $table->enum('processing_status', ['PENDING', 'PROCESSED', 'FAILED', 'IGNORED'])->default('PENDING');
            $table->text('processing_message')->nullable();
            $table->dateTime('received_at');
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();
            $table->index('employee_no');
            $table->index('event_time');
            $table->index('processing_status');
            $table->index('serial_no');
            $table->unique(['device_id', 'serial_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hikvision_events');
    }
};
