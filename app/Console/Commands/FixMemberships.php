<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Membership;

class FixMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberships:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned or duplicated memberships from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting membership cleanup...');

        // Force delete ALL remaining orphaned memberships
        $usedMembershipIds = User::whereNotNull('membership_id')
            ->pluck('membership_id')
            ->unique()
            ->toArray();
            
        $orphanedMemberships = Membership::whereNotIn('id', $usedMembershipIds)->get();

        $deleted = 0;
        foreach ($orphanedMemberships as $om) {
            // Delete certificates related to the orphaned membership completely
            $om->certificates()->delete();
            $om->forceDelete();
            $deleted++;
        }

        $this->info("BINGO! Cleaned up {$deleted} remaining duplicated/orphaned memberships permanently!");
        
        return 0;
    }
}
