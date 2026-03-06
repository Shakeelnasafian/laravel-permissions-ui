<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Shakeelnasafian\PermissionManager\Services\AuditService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $userModel = config('permission-manager.user_model');
        $searchFields = (array) config('permission-manager.user_search_fields', ['name', 'email']);

        $query = $userModel::query();
        if ($search !== '') {
            $query->where(function ($sub) use ($searchFields, $search) {
                foreach ($searchFields as $field) {
                    $sub->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        $users = $query
            ->orderBy(config('permission-manager.user_display_field', 'name'))
            ->paginate((int) config('permission-manager.pagination', 15))
            ->withQueryString();

        return view('permission-manager::users.index', [
            'users' => $users,
            'search' => $search,
            'displayField' => config('permission-manager.user_display_field', 'name'),
        ]);
    }

    public function show($userId): View
    {
        $userModel = config('permission-manager.user_model');
        $user = $userModel::query()->findOrFail($userId);
        $user->load(['roles', 'permissions']);

        $roles = Role::query()
            ->orderBy('hierarchy_level')
            ->orderBy('name')
            ->get()
            ->groupBy('hierarchy_level');

        $permissions = Permission::query()
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => $permission->group ?: 'Ungrouped');

        return view('permission-manager::users.show', [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'userRoleIds' => $user->roles->pluck('id')->all(),
            'userPermissionIds' => $user->permissions->pluck('id')->all(),
            'displayField' => config('permission-manager.user_display_field', 'name'),
        ]);
    }

    public function syncRoles(Request $request, $userId): RedirectResponse
    {
        $userModel = config('permission-manager.user_model');
        $user = $userModel::query()->findOrFail($userId);

        $roleIds = $request->input('role_ids', []);
        $old = $user->roles->pluck('name')->values()->all();

        $roles = Role::query()->whereIn('id', $roleIds)->get();
        $user->syncRoles($roles);
        $user->load('roles');

        $new = $user->roles->pluck('name')->values()->all();

        app(AuditService::class)->log(
            $this->determineSyncAction($old, $new),
            'user_role',
            $user->id,
            $user->getAttribute(config('permission-manager.user_display_field', 'name')),
            ['roles' => $old],
            ['roles' => $new]
        );

        return redirect()
            ->route('permission-manager.users.show', $user)
            ->with('success', 'User roles updated successfully.');
    }

    public function syncPermissions(Request $request, $userId): RedirectResponse
    {
        $userModel = config('permission-manager.user_model');
        $user = $userModel::query()->findOrFail($userId);

        $permissionIds = $request->input('permission_ids', []);
        $old = $user->permissions->pluck('name')->values()->all();

        $permissions = Permission::query()->whereIn('id', $permissionIds)->get();
        $user->syncPermissions($permissions);
        $user->load('permissions');

        $new = $user->permissions->pluck('name')->values()->all();

        app(AuditService::class)->log(
            $this->determineSyncAction($old, $new),
            'user_permission',
            $user->id,
            $user->getAttribute(config('permission-manager.user_display_field', 'name')),
            ['permissions' => $old],
            ['permissions' => $new]
        );

        return redirect()
            ->route('permission-manager.users.show', $user)
            ->with('success', 'User permissions updated successfully.');
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
