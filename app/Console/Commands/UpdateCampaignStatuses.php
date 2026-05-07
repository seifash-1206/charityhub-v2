<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Illuminate\Console\Command;

class UpdateCampaignStatuses extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'campaigns:update-status {--dry-run : Show what would change without saving}';

    /**
     * The console command description.
     */
    protected $description = 'Automatically update campaign statuses based on deadlines and funding goals';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $updated  = 0;
        $skipped  = 0;

        $this->info('🔄 Checking campaign statuses...');

        Campaign::whereIn('status', ['active', 'draft'])->each(function (Campaign $campaign) use ($isDryRun, &$updated, &$skipped) {

            $oldStatus = $campaign->status;

            // Determine new status
            if ($campaign->isExpired()) {
                $newStatus = 'expired';
            } elseif ($campaign->isCompleted()) {
                $newStatus = 'completed';
            } else {
                $newStatus = 'active';
            }

            if ($oldStatus === $newStatus) {
                $skipped++;
                return;
            }

            if (!$isDryRun) {
                $campaign->update(['status' => $newStatus]);
            }

            $this->line("  [{$campaign->id}] \"{$campaign->title}\" → {$oldStatus} → <fg=yellow>{$newStatus}</>");
            $updated++;
        });

        // Also check if completed campaigns should remain completed
        Campaign::where('status', 'completed')->each(function (Campaign $campaign) use ($isDryRun, &$updated) {
            if ($campaign->isExpired() && !$campaign->isCompleted()) {
                if (!$isDryRun) {
                    $campaign->update(['status' => 'expired']);
                }
                $this->line("  [{$campaign->id}] \"{$campaign->title}\" completed → expired");
                $updated++;
            }
        });

        $this->info("✅ Done. Updated: {$updated} | Skipped (no change): {$skipped}");

        if ($isDryRun) {
            $this->warn('(Dry run — no changes saved)');
        }

        return Command::SUCCESS;
    }
}
