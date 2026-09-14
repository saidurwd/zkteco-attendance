<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zk_raw_requests', function (Blueprint $table) {
            $table->id();

            $table->string('serial_number', 100)->nullable();
            $table->string('method', 10);
            $table->string('uri', 500);

            $table->json('query_params')->nullable();
            $table->longText('headers')->nullable();
            $table->longText('body')->nullable();

            $table->string('remote_ip', 45)->nullable();
            $table->timestamp('received_at');

            $table->timestamps();

            $table->index('serial_number');
            $table->index('received_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zk_raw_requests');
    }
};
