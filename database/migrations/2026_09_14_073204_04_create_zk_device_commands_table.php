<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zk_device_commands', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')
                ->constrained('zk_devices')
                ->cascadeOnDelete();

            $table->string('command_id')->unique();
            $table->text('command');

            $table->enum('status', [
                'pending',
                'sent',
                'completed',
                'failed'
            ])->default('pending');

            $table->text('response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['device_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zk_device_commands');
    }
};
