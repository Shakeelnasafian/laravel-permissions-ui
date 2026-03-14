@extends('permission-manager::layouts.app')

@section('title', 'Create Permission')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('permission-manager.permissions.index') }}" class="p-1.5 rounded-lg border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <p class="text-xs" style="color:var(--pm-muted)">Permissions / <span style="color:var(--pm-ink)">Create</span></p>
    </div>

    <div class="max-w-2xl bg-white rounded-xl border p-6" style="border-color:var(--pm-stroke)">
        <h2 class="text-base font-semibold mb-5" style="color:var(--pm-ink)">Permission Details</h2>

        <form method="POST" action="{{ route('permission-manager.permissions.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)"
                    placeholder="e.g. posts.edit">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Guard Name</label>
                <input type="text" name="guard_name" value="{{ old('guard_name', config('permission-manager.guard', 'web')) }}"
                    class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Group <span class="font-normal" style="color:var(--pm-muted)">(optional)</span></label>
                <input type="text" name="group" value="{{ old('group') }}" list="permission-groups"
                    class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)"
                    placeholder="e.g. Posts">
                <datalist id="permission-groups">
                    @foreach ($groups as $group)
                        <option value="{{ $group }}"></option>
                    @endforeach
                </datalist>
                <p class="mt-1 text-xs" style="color:var(--pm-muted)">Type a new group name or select an existing one</p>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t" style="border-color:var(--pm-stroke)">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:var(--pm-accent)">
                    Create Permission
                </button>
                <a href="{{ route('permission-manager.permissions.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
