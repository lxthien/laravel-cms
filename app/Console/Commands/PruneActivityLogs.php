<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class PruneActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity-logs:prune 
                            {--days=90 : Number of days to keep logs}
                            {--dry-run : Show how many records would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old activity logs to keep the database clean';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');

        $cutoffDate = now()->subDays($days);
        $count = ActivityLog::where('created_at', '<', $cutoffDate)->count();

        if ($count === 0) {
            $this->info('No activity logs older than ' . $days . ' days found.');
            return 0;
        }

        if ($dryRun) {
            $this->info("Dry run: Would delete {$count} activity log(s) older than {$days} days.");
            return 0;
        }

        if (!$this->confirm("Are you sure you want to delete {$count} activity log(s) older than {$days} days?")) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->info("Deleting activity logs older than {$days} days...");

        $deleted = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Successfully deleted {$deleted} activity log(s).");

        return 0;
    }
}
