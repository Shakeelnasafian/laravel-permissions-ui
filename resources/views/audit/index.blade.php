@extends('permission-manager::layouts.app')

@section('title', 'Audit Log')

@section('content')
    <div class="mb-6">
        <p class="text-sm" style="color:var(--pm-muted)">Track changes to roles, permissions, and assignments.</p>
    </div>

    <div class="bg-white rounded-xl border mb-5" style="border-color:var(--pm-stroke)">
        <form method="GET" class="p-4">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <select name="entity_type" class="px-3 py-2 rounded-lg text-sm border" style="border-color:var(--pm-stroke)">
                    <option value="">Entity type</option>
                    @foreach (['role', 'permission', 'user_role', 'user_permission'] as $type)
                        <option value="{{ $type }}" {{ ($filters['entity_type'] ?? '') === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>

                <select name="action" class="px-3 py-2 rounded-lg text-sm border" style="border-color:var(--pm-stroke)">
                    <option value="">Action</option>
                    @foreach (['created', 'updated', 'deleted', 'assigned', 'revoked'] as $action)
                        <option value="{{ $action }}" {{ ($filters['action'] ?? '') === $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>

                <select name="user_id" class="px-3 py-2 rounded-lg text-sm border" style="border-color:var(--pm-stroke)">
                    <option value="">All users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ (string) ($filters['user_id'] ?? '') === (string) $user->id ? 'selected' : '' }}>
                            {{ $user->{config('permission-manager.user_display_field', 'name')} }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                    class="px-3 py-2 rounded-lg text-sm border" style="border-color:var(--pm-stroke)">
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                    class="px-3 py-2 rounded-lg text-sm border" style="border-color:var(--pm-stroke)">
            </div>
            <div class="flex flex-wrap gap-3 mt-3">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:var(--pm-accent)">
                    Apply Filters
                </button>
                <a href="{{ route('permission-manager.audit.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden" style="border-color:var(--pm-stroke)">
        <table class="w-full text-sm text-left">
            <thead style="background:#f8fafc;border-bottom:1px solid var(--pm-stroke)">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">When</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Action</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Entity</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">User</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color:var(--pm-stroke)">
                @forelse ($auditLogs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-xs whitespace-nowrap" style="color:var(--pm-muted)">{{ optional($log->created_at)->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @php
                                $actionColors = [
                                    'created' => 'background:#dcfce7;color:#16a34a',
                                    'updated' => 'background:#dbeafe;color:#2563eb',
                                    'deleted' => 'background:#fee2e2;color:#dc2626',
                                    'assigned' => 'background:#ede9fe;color:#7c3aed',
                                    'revoked' => 'background:#fef3c7;color:#d97706',
                                ];
                                $actionStyle = $actionColors[$log->action] ?? 'background:#f1f5f9;color:var(--pm-muted)';
                            @endphp
                            <span class="px-2 py-0.5 rounded text-xs font-medium" style="{{ $actionStyle }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium" style="color:var(--pm-ink)">{{ ucfirst($log->entity_type) }}</p>
                            <p class="text-xs" style="color:var(--pm-muted)">{{ $log->entity_name ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color:var(--pm-muted)">{{ $log->user_id ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-sm" style="color:var(--pm-muted)">No audit entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $auditLogs->links('permission-manager::partials._pagination') }}
@endsection
