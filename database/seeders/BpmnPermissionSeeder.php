<?php

namespace Saccharine\BpmnEngine\Database\Seeders;

use Illuminate\Database\Seeder;
use Saccharine\BpmnEngine\Enums\WorkflowPermission;

class BpmnPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Only run if Spatie's Permission model exists
        $permissionClass = config('permission.models.permission', 'Spatie\Permission\Models\Permission');

        if (! class_exists($permissionClass)) {
            return;
        }

        $guardName = config('filament.auth.guard') ?? config('auth.defaults.guard', 'web');

        foreach (WorkflowPermission::cases() as $permission) {
            $permissionClass::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => $guardName,
            ]);
        }
    }
}