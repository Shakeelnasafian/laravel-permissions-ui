@extends('permission-manager::layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <p class="text-sm" style="color:var(--pm-muted)">Manage role definitions and hierarchy.</p>
        <a href="{{ route('permission-manager.roles.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white"
           style="background:var(--pm-accent)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Create Role
        </a>
    </div>

    <div class="bg-white rounded-xl border mb-5" style="border-color:var(--pm-stroke)">
        <form method="GET" class="flex flex-wrap items-center gap-3 p-4">
            <div class="relative flex-1 min-w-48">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:var(--pm-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search roles..."
                    class="w-full pl-9 pr-4 py-2 rounded-lg text-sm border" style="border-color:var(--pm-stroke)">
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium border" style="border-color:var(--pm-stroke);color:var(--pm-ink)">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden" style="border-color:var(--pm-stroke)">
        <table class="w-full text-sm text-left">
            <thead style="background:#f8fafc;border-bottom:1px solid var(--pm-stroke)">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Name</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Guard</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Description</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Level</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Super</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Perms</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color:var(--pm-stroke)">
                @forelse ($roles as $role)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium" style="color:var(--pm-ink)">{{ $role->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-xs" style="background:#f1f5f9;color:var(--pm-muted)">{{ $role->guard_name }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm max-w-xs truncate" style="color:var(--pm-muted)">{{ $role->description ?: '—' }}</td>
                        <td class="px-4 py-3 text-sm" style="color:var(--pm-muted)">{{ $role->hierarchy_level }}</td>
                        <td class="px-4 py-3">
                            @if ($role->is_super_admin)
                                <span class="px-2 py-0.5 rounded text-xs font-medium" style="background:#ede9fe;color:#7c3aed">Super</span>
                            @else
                                <span style="color:var(--pm-muted)" class="text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm" style="color:var(--pm-muted)">{{ $role->permissions_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('permission-manager.roles.show', $role) }}" class="text-xs font-medium" style="color:var(--pm-accent)">View</a>
                                <a href="{{ route('permission-manager.roles.edit', $role) }}" class="text-xs font-medium" style="color:var(--pm-muted)">Edit</a>
                                <form method="POST" action="{{ route('permission-manager.roles.destroy', $role) }}" onsubmit="return confirm('Delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium" style="color:#dc2626">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-sm" style="color:var(--pm-muted)">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $roles->links('permission-manager::partials._pagination') }}
@endsection
