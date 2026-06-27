<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'admin'],
            ['display_name' => ['ar'=>'سوبر ادمن', 'en'=>'Super Admin']]
        );

        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'admin'],
            ['display_name' => ['ar'=>'ادمن', 'en'=>'Admin']]
        );

        // Assign all permissions to super-admin
        $permissions = \App\Models\Permission::where('guard_name', 'admin')->get();
        $superAdmin->syncPermissions($permissions);
    }
}
