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
                $employeeId = (string) $row->emp_code;

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

                $nameParts = array_filter([trim((string) $row->first_name), trim((string) $row->last_name)]);
                $name = implode(' ', $nameParts);
                $name = $name !== '' ? $name : null;

                $metadata = [
                    'source_id' => $row->id,
                    'staff_id' => $row->staff_id,
                    'contact_no' => $row->contact_no,
                    'joining_date' => $row->joining_date,
                    'date_of_birth' => $row->date_of_birth,
                    'gender' => $row->gender,
                    'blood_group' => $row->blood_group,
                    'nid' => $row->nid,
                    'department_id' => $row->department_id,
                    'designation_id' => $row->designation_id,
                    'location_id' => $row->location_id,
                    'office_shift_id' => $row->office_shift_id,
                    'company_id' => $row->company_id,
                    'role_users_id' => $row->role_users_id,
                    'status_id' => $row->status_id,
                    'religion_id' => $row->religion_id,
                    'marital_status' => $row->marital_status,
                    'exit_date' => $row->exit_date,
                ];

                ZkEmployee::create([
                    'employee_id' => $employeeId,
                    'name' => $name,
                    'email' => $row->email,
                    'department' => $row->department_id !== null ? (string) $row->department_id : null,
                    'position' => $row->designation_id !== null ? (string) $row->designation_id : null,
                    'site_code' => $row->location_id !== null ? (string) $row->location_id : null,
                    'is_active' => (bool) $row->is_active,
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
