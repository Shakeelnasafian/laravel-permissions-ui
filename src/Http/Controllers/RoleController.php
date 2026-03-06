<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Shakeelnasafian\PermissionManager\Services\AuditService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $query = Role::query()->withCount('permissions');
        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query
            ->orderBy('hierarchy_level')
            ->orderBy('name')
            ->paginate((int) config('permission-manager.pagination', 15))
            ->withQueryString();

        return view('permission-manager::roles.index', [
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('permission-manager::roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $guardName = $request->input('guard_name', config('permission-manager.guard', 'web'));

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique(Role::class)->where(fn ($query) => $query->where('guard_name', $guardName)),
            ],
            'guard_name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'hierarchy_level' => ['nullable', 'integer', 'min:0'],
            'is_super_admin' => ['nullable', 'boolean'],
        ]);

        $role = new Role();
        $role->name = $validated['name'];
        $role->guard_name = $guardName;
        $role->description = $validated['description'] ?? null;
        $role->hierarchy_level = (int) ($validated['hierarchy_level'] ?? 0);
        $role->is_super_admin = (bool) ($validated['is_super_admin'] ?? false);
        $role->save();

        return redirect()
            ->route('permission-manager.roles.show', $role)
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role): View
    {
        $role->load('permissions');

        $permissionsQuery = Permission::query()->orderBy('group')->orderBy('name');
        $permissions = $permissionsQuery->get();
        $groupedPermissions = $permissions->groupBy(fn (Permission $permission) => $permission->group ?: 'Ungrouped');

        $userModel = config('permission-manager.user_model');
        $users = $userModel::query()
            ->whereHas('roles', fn ($query) => $query->where('roles.id', $role->id))
            ->orderBy(config('permission-manager.user_display_field', 'name'))
            ->get();

        return view('permission-manager::roles.show', [
            'role' => $role,
            'groupedPermissions' => $groupedPermissions,
            'rolePermissionIds' => $role->permissions->pluck('id')->all(),
            'users' => $users,
        ]);
    }

    public function edit(Role $role): View
    {
        return view('permission-manager::roles.edit', [
            'role' => $role,
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $guardName = $request->input('guard_name', $role->guard_name ?? config('permission-manager.guard', 'web'));

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique(Role::class)
                    ->ignore($role->id)
                    ->where(fn ($query) => $query->where('guard_name', $guardName)),
            ],
            'guard_name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'hierarchy_level' => ['nullable', 'integer', 'min:0'],
            'is_super_admin' => ['nullable', 'boolean'],
        ]);

        $role->name = $validated['name'];
        $role->guard_name = $guardName;
        $role->description = $validated['description'] ?? null;
        $role->hierarchy_level = (int) ($validated['hierarchy_level'] ?? 0);
        $role->is_super_admin = (bool) ($validated['is_super_admin'] ?? false);
        $role->save();

        return redirect()
            ->route('permission-manager.roles.show', $role)
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()
            ->route('permission-manager.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    public function syncPermissions(Request $request, Role $role): RedirectResponse
    {
        $permissionIds = $request->input('permission_ids', []);
        $permissions = Permission::query()->whereIn('id', $permissionIds)->get();

        $old = $role->permissions->pluck('name')->values()->all();
        $role->syncPermissions($permissions);
        $role->load('permissions');
        $new = $role->permissions->pluck('name')->values()->all();

        app(AuditService::class)->log(
            $this->determineSyncAction($old, $new),
            'role',
            $role->id,
            $role->name,
            ['permissions' => $old],
            ['permissions' => $new]
        );

        return redirect()
            ->route('permission-manager.roles.show', $role)
            ->with('success', 'Role permissions updated successfully.');
    }

    private function determineSyncAction(array $old, array $new): string
    {
        $oldSet = collect($old);
        $newSet = collect($new);

        $added = $newSet->diff($oldSet)->isNotEmpty();
        $removed = $oldSet->diff($newSet)->isNotEmpty();

        if ($added && ! $removed) {
            return 'assigned';
        }

        if ($removed && ! $added) {
            return 'revoked';
        }

        return 'updated';
    }
}
