@extends('permission-manager::layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <p class="text-sm" style="color:var(--pm-muted)">Snapshot of roles, permissions, and recent activity.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium" style="color:var(--pm-muted)">Total Roles</p>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#ede9fe">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#7c3aed" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5.916-3.5M9 20H4v-2a4 4 0 015.916-3.5M15 11a4 4 0 11-8 0 4 4 0 018 0zm6 9v-2a4 4 0 00-3-3.87M3 18v-2a4 4 0 013-3.87"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-2xl font-semibold" style="color:var(--pm-ink)">{{ $totalRoles }}</p>
            <p class="mt-1 text-xs" style="color:var(--pm-muted)">Defined roles</p>
        </div>

        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium" style="color:var(--pm-muted)">Permissions</p>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#dbeafe">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-3.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-2xl font-semibold" style="color:var(--pm-ink)">{{ $totalPermissions }}</p>
            <p class="mt-1 text-xs" style="color:var(--pm-muted)">Across all groups</p>
        </div>

        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium" style="color:var(--pm-muted)">Users</p>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#dcfce7">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#16a34a" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-2xl font-semibold" style="color:var(--pm-ink)">{{ $totalUsers }}</p>
            <p class="mt-1 text-xs" style="color:var(--pm-muted)">Registered users</p>
        </div>
    </div>

    <div class="mt-5 grid gap-5 lg:grid-cols-2">
        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <h3 class="text-sm font-semibold" style="color:var(--pm-ink)">Permissions by Group</h3>
            <div class="mt-4 flex flex-wrap gap-2">
                @forelse ($groupCounts as $group)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium" style="background:#f1f5f9;color:var(--pm-ink);border:1px solid var(--pm-stroke)">
                        {{ $group->group ?: 'Ungrouped' }}
                        <span class="font-semibold px-1 rounded" style="background:#ede9fe;color:#7c3aed">{{ $group->total }}</span>
                    </span>
                @empty
                    <p class="text-sm" style="color:var(--pm-muted)">No permission groups found yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <h3 class="text-sm font-semibold" style="color:var(--pm-ink)">Recent Activity</h3>
            <div class="mt-3 divide-y" style="divide-color:var(--pm-stroke)">
                @if (! empty($auditLogs) && count($auditLogs) > 0)
                    @foreach ($auditLogs as $log)
                        <div class="flex items-start gap-3 py-2.5">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#f1f5f9">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" style="color:var(--pm-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm" style="color:var(--pm-ink)">
                                    <span class="font-medium">{{ ucfirst($log->action) }}</span>
                                    {{ $log->entity_type }} <span style="color:var(--pm-muted)">{{ $log->entity_name }}</span>
                                </p>
                                <p class="text-xs mt-0.5" style="color:var(--pm-muted)">
                                    {{ optional($log->created_at)->format('M d, Y H:i') }}
                                    @if ($log->user_id) &mdash; User #{{ $log->user_id }}@endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm py-2" style="color:var(--pm-muted)">Audit log is empty or disabled.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
