@extends('permission-manager::layouts.app')

@section('title', 'Permissions')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <p class="text-sm" style="color:var(--pm-muted)">Group and maintain granular permission rules.</p>
        <a href="{{ route('permission-manager.permissions.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white"
           style="background:var(--pm-accent)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Create Permission
        </a>
    </div>

    <div class="bg-white rounded-xl border mb-5" style="border-color:var(--pm-stroke)">
        <form method="GET" class="flex flex-wrap items-center gap-3 p-4">
            <div class="relative flex-1 min-w-48">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:var(--pm-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search permissions..."
                    class="w-full pl-9 pr-4 py-2 rounded-lg text-sm border" style="border-color:var(--pm-stroke)">
            </div>
            <select name="group" class="px-3 py-2 rounded-lg text-sm border" style="border-color:var(--pm-stroke)">
                <option value="">All groups</option>
                @foreach ($groups as $option)
                    <option value="{{ $option }}" {{ $group === $option ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium border" style="border-color:var(--pm-stroke);color:var(--pm-ink)">
                Filter
            </button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse ($groupedPermissions as $groupName => $items)
            <div class="bg-white rounded-xl border overflow-hidden" style="border-color:var(--pm-stroke)">
                <div class="flex items-center justify-between px-4 py-3 border-b" style="background:#f8fafc;border-color:var(--pm-stroke)">
                    <h3 class="text-sm font-semibold" style="color:var(--pm-ink)">{{ $groupName }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded" style="background:#ede9fe;color:#7c3aed">{{ $items->count() }} permissions</span>
                </div>
                <table class="w-full text-sm text-left">
                    <thead style="border-bottom:1px solid var(--pm-stroke)">
                        <tr>
                            <th class="px-4 py-2.5 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Name</th>
                            <th class="px-4 py-2.5 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Guard</th>
                            <th class="px-4 py-2.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--pm-stroke)">
                        @foreach ($items as $permission)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-medium" style="color:var(--pm-ink)">{{ $permission->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs" style="background:#f1f5f9;color:var(--pm-muted)">{{ $permission->guard_name }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('permission-manager.permissions.edit', $permission) }}" class="text-xs font-medium" style="color:var(--pm-accent)">Edit</a>
                                        <form method="POST" action="{{ route('permission-manager.permissions.destroy', $permission) }}" onsubmit="return confirm('Delete this permission?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-medium" style="color:#dc2626">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="bg-white rounded-xl border p-10 text-center" style="border-color:var(--pm-stroke)">
                <p class="text-sm" style="color:var(--pm-muted)">No permissions found.</p>
            </div>
        @endforelse
    </div>

    {{ $permissions->links('permission-manager::partials._pagination') }}
@endsection
