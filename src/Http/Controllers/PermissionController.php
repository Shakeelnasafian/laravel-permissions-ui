<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $group = trim((string) $request->query('group', ''));

        $query = Permission::query();
        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($group !== '') {
            $query->where('group', $group);
        }

        $permissions = $query
            ->orderBy('group')
            ->orderBy('name')
            ->paginate((int) config('permission-manager.pagination', 15))
            ->withQueryString();

        $groupedPermissions = $permissions->getCollection()
            ->groupBy(fn (Permission $permission) => $permission->group ?: 'Ungrouped');

        $groups = Permission::query()
            ->select('group')
            ->whereNotNull('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->values()
            ->all();

        return view('permission-manager::permissions.index', [
            'permissions' => $permissions,
            'groupedPermissions' => $groupedPermissions,
            'groups' => $groups,
            'search' => $search,
            'group' => $group,
        ]);
    }

    public function create(): View
    {
        $groups = Permission::query()
            ->select('group')
            ->whereNotNull('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->values()
            ->all();

        return view('permission-manager::permissions.create', [
            'groups' => $groups,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $guardName = $request->input('guard_name', config('permission-manager.guard', 'web'));

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique(Permission::class)->where(fn ($query) => $query->where('guard_name', $guardName)),
            ],
            'guard_name' => ['nullable', 'string'],
            'group' => ['nullable', 'string'],
        ]);

        $permission = new Permission();
        $permission->name = $validated['name'];
        $permission->guard_name = $guardName;
        $permission->group = $validated['group'] ?? null;
        $permission->save();

        return redirect()
            ->route('permission-manager.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission): View
    {
        $groups = Permission::query()
            ->select('group')
            ->whereNotNull('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group')
            ->values()
            ->all();

        return view('permission-manager::permissions.edit', [
            'permission' => $permission,
            'groups' => $groups,
        ]);
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $guardName = $request->input('guard_name', $permission->guard_name ?? config('permission-manager.guard', 'web'));

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique(Permission::class)
                    ->ignore($permission->id)
                    ->where(fn ($query) => $query->where('guard_name', $guardName)),
            ],
            'guard_name' => ['nullable', 'string'],
            'group' => ['nullable', 'string'],
        ]);

        $permission->name = $validated['name'];
        $permission->guard_name = $guardName;
        $permission->group = $validated['group'] ?? null;
        $permission->save();

        return redirect()
            ->route('permission-manager.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()
            ->route('permission-manager.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
