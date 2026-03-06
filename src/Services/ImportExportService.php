<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ImportExportService
{
    public function buildExportData(string $include = 'both'): array
    {
        $include = strtolower($include);
        $data = [];

        if ($include === 'roles' || $include === 'both') {
            $data['roles'] = Role::query()
                ->with('permissions')
                ->orderBy('hierarchy_level')
                ->orderBy('name')
                ->get()
                ->map(function (Role $role) {
                    return [
                        'name' => $role->name,
                        'guard_name' => $role->guard_name,
                        'description' => $role->description,
                        'hierarchy_level' => $role->hierarchy_level,
                        'is_super_admin' => (bool) $role->is_super_admin,
                        'permissions' => $role->permissions->pluck('name')->values()->all(),
                    ];
                })
                ->values()
                ->all();
        }

        if ($include === 'permissions' || $include === 'both') {
            $data['permissions'] = Permission::query()
                ->orderBy('group')
                ->orderBy('name')
                ->get()
                ->map(function (Permission $permission) {
                    return [
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                        'group' => $permission->group,
                    ];
                })
                ->values()
                ->all();
        }

        return $data;
    }

    public function buildCsvRows(string $include = 'both'): array
    {
        $data = $this->buildExportData($include);
        $rows = [];

        $rows[] = [
            'type',
            'name',
            'guard_name',
            'group',
            'description',
            'hierarchy_level',
            'is_super_admin',
            'permissions',
        ];

        foreach ($data['permissions'] ?? [] as $permission) {
            $rows[] = [
                'permission',
                $permission['name'],
                $permission['guard_name'],
                $permission['group'],
                null,
                null,
                null,
                null,
            ];
        }

        foreach ($data['roles'] ?? [] as $role) {
            $rows[] = [
                'role',
                $role['name'],
                $role['guard_name'],
                null,
                $role['description'],
                $role['hierarchy_level'],
                $role['is_super_admin'] ? '1' : '0',
                implode('|', $role['permissions'] ?? []),
            ];
        }

        return $rows;
    }

    public function import(array $payload): array
    {
        $results = [
            'permissions_created' => 0,
            'permissions_updated' => 0,
            'roles_created' => 0,
            'roles_updated' => 0,
        ];

        $permissions = Arr::get($payload, 'permissions', []);
        foreach ($permissions as $permissionData) {
            $guard = $permissionData['guard_name'] ?? config('permission-manager.guard', 'web');
            $group = $permissionData['group'] ?? null;

            $permission = Permission::firstOrNew([
                'name' => $permissionData['name'],
                'guard_name' => $guard,
            ]);

            $wasNew = ! $permission->exists;
            $permission->group = $group;

            if ($permission->isDirty()) {
                $permission->save();
                if ($wasNew) {
                    $results['permissions_created']++;
                } else {
                    $results['permissions_updated']++;
                }
            }
        }

        $roles = Arr::get($payload, 'roles', []);
        foreach ($roles as $roleData) {
            $guard = $roleData['guard_name'] ?? config('permission-manager.guard', 'web');

            $role = Role::firstOrNew([
                'name' => $roleData['name'],
                'guard_name' => $guard,
            ]);

            $wasNew = ! $role->exists;
            $role->description = $roleData['description'] ?? $role->description;
            $role->hierarchy_level = (int) ($roleData['hierarchy_level'] ?? $role->hierarchy_level ?? 0);
            $role->is_super_admin = (bool) ($roleData['is_super_admin'] ?? $role->is_super_admin ?? false);

            if ($role->isDirty()) {
                $role->save();
                if ($wasNew) {
                    $results['roles_created']++;
                } else {
                    $results['roles_updated']++;
                }
            }

            $permissionNames = collect($roleData['permissions'] ?? [])
                ->filter()
                ->map(fn ($name) => (string) $name)
                ->values();

            if ($permissionNames->isNotEmpty()) {
                $permissionsToSync = $permissionNames->map(function (string $name) use ($guard) {
                    return Permission::firstOrCreate([
                        'name' => $name,
                        'guard_name' => $guard,
                    ]);
                });

                $role->syncPermissions($permissionsToSync);
            }
        }

        return $results;
    }

    public function parseCsv(string $csvContent): array
    {
        $lines = preg_split('/\r\n|\n|\r/', trim($csvContent));
        $rows = array_map('str_getcsv', $lines);

        if (count($rows) <= 1) {
            return [];
        }

        $header = array_map(fn ($value) => Str::of($value)->trim()->value(), array_shift($rows));
        $records = [];

        foreach ($rows as $row) {
            $record = [];
            foreach ($header as $index => $key) {
                $record[$key] = $row[$index] ?? null;
            }
            $records[] = $record;
        }

        $payload = ['roles' => [], 'permissions' => []];
        foreach ($records as $record) {
            $type = strtolower((string) ($record['type'] ?? ''));

            if ($type === 'permission') {
                $payload['permissions'][] = [
                    'name' => $record['name'],
                    'guard_name' => $record['guard_name'],
                    'group' => $record['group'] ?: null,
                ];
            }

            if ($type === 'role') {
                $permissions = [];
                if (! empty($record['permissions'])) {
                    $permissions = array_filter(explode('|', (string) $record['permissions']));
                }

                $payload['roles'][] = [
                    'name' => $record['name'],
                    'guard_name' => $record['guard_name'],
                    'description' => $record['description'] ?: null,
                    'hierarchy_level' => (int) ($record['hierarchy_level'] ?? 0),
                    'is_super_admin' => (bool) ($record['is_super_admin'] ?? false),
                    'permissions' => $permissions,
                ];
            }
        }

        return $payload;
    }
}
