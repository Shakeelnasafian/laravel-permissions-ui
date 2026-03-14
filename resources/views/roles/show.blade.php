@extends('permission-manager::layouts.app')

@section('title', 'Role: ' . $role->name)

@section('content')
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('permission-manager.roles.index') }}" class="p-1.5 rounded-lg border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-lg font-semibold" style="color:var(--pm-ink)">{{ $role->name }}</h2>
                    @if ($role->is_super_admin)
                        <span class="px-2 py-0.5 rounded text-xs font-medium" style="background:#ede9fe;color:#7c3aed">Super Admin</span>
                    @endif
                </div>
                <p class="text-xs mt-0.5" style="color:var(--pm-muted)">
                    Guard: {{ $role->guard_name }} &bull; Level {{ $role->hierarchy_level }}
                    @if ($role->description) &bull; {{ $role->description }}@endif
                </p>
            </div>
        </div>
        <a href="{{ route('permission-manager.roles.edit', $role) }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border text-sm font-medium"
           style="border-color:var(--pm-stroke);color:var(--pm-ink)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Role
        </a>
    </div>

    <div class="grid gap-5 lg:grid-cols-[3fr_2fr]">
        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold" style="color:var(--pm-ink)">Permissions</h3>
                    <p class="text-xs mt-0.5" style="color:var(--pm-muted)">Assign permissions to this role</p>
                </div>
            </div>

            <form method="POST" action="{{ route('permission-manager.roles.sync-permissions', $role) }}" class="space-y-3">
                @csrf
                @foreach ($groupedPermissions as $group => $permissions)
                    <div class="rounded-lg border p-4" style="border-color:var(--pm-stroke)">
                        <h4 class="text-xs font-semibold uppercase tracking-wide mb-3" style="color:var(--pm-muted)">{{ $group }}</h4>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}"
                                        {{ in_array($permission->id, $rolePermissionIds, true) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded" style="accent-color:var(--pm-accent)">
                                    <span style="color:var(--pm-ink)">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:var(--pm-accent)">
                        Save Permissions
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <h3 class="text-sm font-semibold mb-1" style="color:var(--pm-ink)">Assigned Users</h3>
            <p class="text-xs mb-4" style="color:var(--pm-muted)">Users holding this role</p>

            <div class="space-y-2">
                @forelse ($users as $user)
                    <div class="flex items-center gap-3 p-3 rounded-lg border" style="border-color:var(--pm-stroke)">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-semibold" style="background:#ede9fe;color:#7c3aed">
                            {{ strtoupper(substr($user->{config('permission-manager.user_display_field', 'name')}, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium truncate" style="color:var(--pm-ink)">{{ $user->{config('permission-manager.user_display_field', 'name')} }}</p>
                            @if (isset($user->email))
                                <p class="text-xs truncate" style="color:var(--pm-muted)">{{ $user->email }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm py-4 text-center" style="color:var(--pm-muted)">No users assigned yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
