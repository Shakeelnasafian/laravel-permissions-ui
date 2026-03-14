@extends('permission-manager::layouts.app')

@section('title', 'Users')

@section('content')
    <div class="mb-6">
        <p class="text-sm" style="color:var(--pm-muted)">Assign roles and direct permissions to users.</p>
    </div>

    <div class="bg-white rounded-xl border mb-5" style="border-color:var(--pm-stroke)">
        <form method="GET" class="flex flex-wrap items-center gap-3 p-4">
            <div class="relative flex-1 min-w-48">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:var(--pm-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search users..."
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
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">{{ ucfirst($displayField) }}</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide" style="color:var(--pm-muted)">Email</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color:var(--pm-stroke)">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-semibold" style="background:#ede9fe;color:#7c3aed">
                                    {{ strtoupper(substr($user->{$displayField}, 0, 1)) }}
                                </div>
                                <span class="font-medium" style="color:var(--pm-ink)">{{ $user->{$displayField} }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm" style="color:var(--pm-muted)">{{ $user->email ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('permission-manager.users.show', $user) }}" class="text-xs font-medium" style="color:var(--pm-accent)">Manage</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-10 text-center text-sm" style="color:var(--pm-muted)">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links('permission-manager::partials._pagination') }}
@endsection
