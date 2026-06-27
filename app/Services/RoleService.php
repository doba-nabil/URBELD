<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleService
{
    public function getAll()
    {
        return Role::all();
    }

    public function getById($id)
    {
        return Role::findOrFail($id);
    }

    public function create(array $data)
    {
        // Use name if already set (from RoleRequest), otherwise create from display_name
        if (empty($data['name'])) {
            if (!empty($data['display_name']['en'])) {
                $data['name'] = Str::slug($data['display_name']['en']);
            } elseif (!empty($data['display_name']['ar'])) {
                $data['name'] = Str::slug($data['display_name']['ar']);
            }
        }
        
        $data['display_name'] = $data['display_name'];

        $role = Role::create($data);
        if (!empty($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        }
        return $role;
    }

    public function update(Role $role, array $data)
    {
        // Use name if already set (from RoleRequest), otherwise create from display_name
        if (empty($data['name'])) {
            if (!empty($data['display_name']['en'])) {
                $data['name'] = Str::slug($data['display_name']['en']);
            } elseif (!empty($data['display_name']['ar'])) {
                $data['name'] = Str::slug($data['display_name']['ar']);
            }
        }
        
        $role->update($data);
        if (!empty($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }
        return $role;
    }


    public function delete($id)
    {
        $role = Role::findOrFail($id);
        return $role->delete();
    }
}
