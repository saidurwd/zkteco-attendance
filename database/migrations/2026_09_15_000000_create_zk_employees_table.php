<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zk_employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 100)->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('site_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('site_code');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zk_employees');
    }
};
