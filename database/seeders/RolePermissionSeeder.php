<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    private $permissions = [
        'dashboard' => [
            'view',
        ],

        'user' => [
            'view',
            'create',
            'edit',
            'delete',
        ],

        'resident' => [
            'view',
            'create',
            'edit',
            'delete',
        ],

        'report' => [
            'view',
            'create',
            'edit',
            'delete',
        ],

        'report-status' => [
            'view',
            'create',
            'edit',
            'delete',
        ],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permissions as $key => $value) {
            foreach ($value as $permissions) {
                Permission::firstOrCreate([
                    'name' => $key . '-' . $permissions
                ]);
            }
        }

        Role::firstOrCreate(['name' => 'resident', 'guard_name' => 'web']);
    }
}
