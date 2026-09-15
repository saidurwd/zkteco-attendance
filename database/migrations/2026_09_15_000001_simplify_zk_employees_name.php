<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zk_employees', function (Blueprint $table) {
            $table->string('name')->after('employee_id')->nullable();
        });

        \DB::table('zk_employees')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''));
                \DB::table('zk_employees')->where('id', $row->id)->update(['name' => $name]);
            }
        });

        Schema::table('zk_employees', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->dropColumn('first_name', 'last_name');
        });
    }

    public function down(): void
    {
        Schema::table('zk_employees', function (Blueprint $table) {
            $table->string('first_name')->after('employee_id')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
        });

        \DB::table('zk_employees')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $parts = preg_split('/\s+/', trim((string) $row->name), 2);
                \DB::table('zk_employees')->where('id', $row->id)->update([
                    'first_name' => $parts[0] ?? null,
                    'last_name' => $parts[1] ?? null,
                ]);
            }
        });

        Schema::table('zk_employees', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->dropColumn('name');
        });
    }
};
