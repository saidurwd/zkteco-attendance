<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zk_employee_mappings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')
                ->constrained('zk_devices')
                ->cascadeOnDelete();

            $table->string('device_pin', 50);
            $table->string('employee_code', 100);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['device_id', 'device_pin']);
            $table->index('employee_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zk_employee_mappings');
    }
};
