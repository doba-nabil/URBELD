<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:clean';

    protected $description = 'Clean up duplicate and orphaned admin permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting permissions cleanup for admin guard...');

        // Wipe all admin permissions to eliminate duplicates and old entries
        \App\Models\Permission::where('guard_name', 'admin')->delete();
        
        // Re-seed to get a clean, updated set of permissions
        $this->call('db:seed', ['--class' => 'PermissionSeeder']);

        $this->info('All admin permissions have been wiped and re-seeded successfully.');
    }
}
