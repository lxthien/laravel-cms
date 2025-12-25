<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;

class FixActivityLogChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:activity-log-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize nested "changes" key in activity_logs.properties (fix double-wrapped changes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning activity logs...');

        $query = ActivityLog::whereNotNull('properties');
        $total = $query->count();

        if ($total === 0) {
            $this->info('No activity logs with properties found.');
            return 0;
        }

        $this->info("Found {$total} logs to inspect.");

        $fixed = 0;

        $query->chunkById(200, function ($logs) use (&$fixed) {
            foreach ($logs as $log) {
                $props = $log->properties;

                if (!is_array($props)) {
                    continue;
                }

                // If properties['changes'] exists and itself contains a 'changes' key,
                // replace properties['changes'] with that inner value.
                if (isset($props['changes']) && is_array($props['changes']) && isset($props['changes']['changes']) && is_array($props['changes']['changes'])) {
                    $props['changes'] = $props['changes']['changes'];
                    $log->properties = $props;
                    $log->save();
                    $fixed++;
                    continue;
                }

                // Handle case where properties['changes'] is a JSON string containing nested 'changes'
                if (isset($props['changes']) && is_string($props['changes'])) {
                    $decoded = json_decode($props['changes'], true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded['changes']) && is_array($decoded['changes'])) {
                        $props['changes'] = $decoded['changes'];
                        $log->properties = $props;
                        $log->save();
                        $fixed++;
                    }
                }
            }
        });

        $this->info("Completed. Fixed: {$fixed} records.");
        return 0;
    }
}
