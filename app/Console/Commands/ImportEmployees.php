<?php

namespace App\Console\Commands;

use App\Models\ZkEmployee;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('zkteco:import-employees')]
#[Description('Import employees from employees table into zk_employees')]
class ImportEmployees extends Command
{
    protected $signature = 'zkteco:import-employees';
    protected $description = 'Import employees from employees table into zk_employees';

    public function handle(): void
    {
        $total = DB::table('employees')->count();
        if ($total === 0) {
            $this->info('No employees found in source table.');
            return;
        }

        $this->info("Found {$total} employees to import.");
        $progress = $this->output->createProgressBar($total);
        $progress->start();

        $imported = 0;
        $skipped = 0;

        DB::table('employees')->orderBy('id')->chunk(100, function ($rows) use ($progress, &$imported, &$skipped) {
            foreach ($rows as $row) {
                $employeeId = (string) $row->employee_code;

                if (empty($employeeId)) {
                    $skipped++;
                    $progress->advance();
                    continue;
                }

                $exists = ZkEmployee::where('employee_id', $employeeId)->exists();
                if ($exists) {
                    $skipped++;
                    $progress->advance();
                    continue;
                }

                $name = trim((string) $row->employee_name);

                $metadata = [
                    'source_id' => $row->id,
                    'phone' => $row->phone,
                    'joining_date' => $row->joining_date,
                    'department_id' => $row->department_id,
                    'location_id' => $row->location_id,
                ];

                ZkEmployee::create([
                    'employee_id' => $employeeId,
                    'name' => $name,
                    'email' => $row->email,
                    'department' => $row->department_id !== null ? (string) $row->department_id : null,
                    'position' => $row->designation,
                    'site_code' => $row->location_id !== null ? (string) $row->location_id : null,
                    'is_active' => $row->status === 'active',
                    'metadata' => $metadata,
                ]);

                $imported++;
                $progress->advance();
            }
        });

        $progress->finish();
        $this->info('');
        $this->info("Import complete.");
        $this->info("Imported: {$imported}");
        $this->info("Skipped: {$skipped}");
    }
}
