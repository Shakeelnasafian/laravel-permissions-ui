@extends('permission-manager::layouts.app')

@section('title', 'User: ' . $user->{$displayField})

@section('content')
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('permission-manager.users.index') }}" class="p-1.5 rounded-lg border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 font-semibold" style="background:#ede9fe;color:#7c3aed">
                    {{ strtoupper(substr($user->{$displayField}, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-base font-semibold" style="color:var(--pm-ink)">{{ $user->{$displayField} }}</h2>
                    @if (! empty($user->email))
                        <p class="text-xs" style="color:var(--pm-muted)">{{ $user->email }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <h3 class="text-sm font-semibold mb-1" style="color:var(--pm-ink)">Assign Roles</h3>
            <p class="text-xs mb-4" style="color:var(--pm-muted)">Roles control bundled permissions</p>

            <form method="POST" action="{{ route('permission-manager.users.sync-roles', $user) }}" class="space-y-3">
                @csrf
                @foreach ($roles as $level => $roleGroup)
                    <div class="rounded-lg border p-3" style="border-color:var(--pm-stroke)">
                        <h4 class="text-xs font-semibold uppercase tracking-wide mb-2.5" style="color:var(--pm-muted)">Level {{ $level }}</h4>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach ($roleGroup as $role)
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" name="role_ids[]" value="{{ $role->id }}"
                                        {{ in_array($role->id, $userRoleIds, true) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded" style="accent-color:var(--pm-accent)">
                                    <span style="color:var(--pm-ink)">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:var(--pm-accent)">
                    Save Roles
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <h3 class="text-sm font-semibold mb-1" style="color:var(--pm-ink)">Direct Permissions</h3>
            <p class="text-xs mb-4" style="color:var(--pm-muted)">Individual overrides for this user</p>

            <form method="POST" action="{{ route('permission-manager.users.sync-permissions', $user) }}" class="space-y-3">
                @csrf
                @foreach ($permissions as $group => $permissionGroup)
                    <div class="rounded-lg border p-3" style="border-color:var(--pm-stroke)">
                        <h4 class="text-xs font-semibold uppercase tracking-wide mb-2.5" style="color:var(--pm-muted)">{{ $group }}</h4>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach ($permissionGroup as $permission)
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}"
                                        {{ in_array($permission->id, $userPermissionIds, true) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded" style="accent-color:var(--pm-accent)">
                                    <span style="color:var(--pm-ink)">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:var(--pm-accent)">
                    Save Permissions
                </button>
            </form>
        </div>
    </div>
@endsection
