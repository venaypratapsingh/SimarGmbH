<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class CleanDuplicateEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-duplicate-emails {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate email addresses in the employees table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting duplicate email cleanup...');

        try {
            // Find duplicate emails
            $duplicates = DB::table('employees')
                ->select('email', DB::raw('COUNT(*) as count'))
                ->groupBy('email')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            if ($duplicates->isEmpty()) {
                $this->info('No duplicate emails found.');
                return;
            }

            $this->info("Found {$duplicates->count()} email(s) with duplicates:");

            foreach ($duplicates as $duplicate) {
                $this->line("- {$duplicate->email} ({$duplicate->count} records)");

                if ($this->option('dry-run')) {
                    // Show what would be deleted
                    $records = Employee::where('email', $duplicate->email)
                        ->orderBy('created_at')
                        ->get();

                    $this->info("  Would keep: ID {$records->first()->id} (oldest)");
                    foreach ($records->slice(1) as $record) {
                        $this->warn("  Would delete: ID {$record->id}");
                    }
                } else {
                    // Actually delete duplicates, keeping the oldest record
                    $records = Employee::where('email', $duplicate->email)
                        ->orderBy('created_at')
                        ->get();

                    $kept = $records->first();
                    $deleted = $records->slice(1);

                    $this->info("  Keeping: ID {$kept->id} (oldest)");
                    foreach ($deleted as $record) {
                        $record->delete();
                        $this->warn("  Deleted: ID {$record->id}");
                    }
                }
            }

            if (!$this->option('dry-run')) {
                $this->info('Duplicate cleanup completed successfully!');
            } else {
                $this->info('Dry run completed. Use without --dry-run to actually delete duplicates.');
            }

        } catch (\Exception $e) {
            $this->error('Error during cleanup: ' . $e->getMessage());
            return 1;
        }
    }
}
