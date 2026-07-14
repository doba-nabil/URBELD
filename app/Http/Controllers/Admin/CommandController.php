<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class CommandController extends Controller
{
    /**
     * Display the command runner UI.
     */
    public function index()
    {
        // Only super-admin or the original admin email 'admin@ERSAA.com' should see this.
        $user = auth('admin')->user();
        if (!$user->hasRole('super-admin', 'admin') && $user->email !== 'admin@ERSAA.com') {
            abort(403);
        }

        return view('dashboard.commands.index');
    }

    /**
     * Execute the provided command.
     */
    public function execute(Request $request)
    {
        $user = auth('admin')->user();
        if (!$user->hasRole('super-admin', 'admin') && $user->email !== 'admin@ERSAA.com') {
            return response()->json(['output' => 'Unauthorized'], 403);
        }

        $command = $request->input('command');
        $type = $request->input('type', 'artisan'); // 'artisan' or 'shell'

        if (empty($command)) {
            return response()->json(['output' => 'Command is empty']);
        }

        try {
            $output = '';
            if ($type === 'artisan') {
                // Remove 'php artisan ' if present
                $artisanCommand = preg_replace('/^php artisan\s+/', '', $command);
                
                // Use Artisan::call
                Artisan::call($artisanCommand);
                $output = Artisan::output();
            } else {
                // Run as shell command
                $output = shell_exec($command . ' 2>&1');
            }

            return response()->json([
                'status' => 'success',
                'output' => $output ?: 'Command executed with no output.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'output' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Specialized API to clean and sync permissions and update super-admin role
     */
    public function syncAdminPermissions()
    {
        try {
            // 1. Run the custom cleanup command
            Artisan::call('permissions:clean');
            $seederOutput = Artisan::output();

            // 2. Ensure super-admin role exists with 'admin' guard
            $superAdmin = \App\Models\Role::firstOrCreate(
                ['name' => 'super-admin', 'guard_name' => 'admin'],
                ['display_name' => ['ar'=>'سوبر ادمن', 'en'=>'Super Admin']]
            );

            // 4. Sync all permissions to super-admin role
            $permissions = \App\Models\Permission::where('guard_name', 'admin')->get();
            $superAdmin->syncPermissions($permissions);

            // 5. Forget cached permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // 6. Update the specific admins and current user
            $emails = ['admin@ERSAA.com'];
            $currentUser = auth('admin')->user();
            if ($currentUser) {
                $emails[] = $currentUser->email;
            }
            
            $emails = array_unique($emails);
            $foundUsers = [];

            foreach ($emails as $email) {
                $user = \App\Models\User::where('email', $email)->first();
                if ($user) {
                    $user->update(['is_admin' => true]);
                    if (!$user->hasRole('super-admin', 'admin')) {
                        $user->assignRole($superAdmin);
                    }
                    $foundUsers[] = $email;
                }
            }

            $output = "Database cleaned and permissions re-seeded fresh.\n";
            $output .= "Permission Seeding Output:\n" . ($seederOutput ?: "No output from seeder.") . "\n\n";
            $output .= "Successfully synced all permissions and updated super-admin role for: " . implode(', ', $foundUsers);

            return response()->json([
                'status' => 'success',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'output' => "Error: " . $e->getMessage()
            ], 500);
        }
    }
}
