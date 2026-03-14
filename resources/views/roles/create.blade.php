@extends('permission-manager::layouts.app')

@section('title', 'Create Role')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('permission-manager.roles.index') }}" class="p-1.5 rounded-lg border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <p class="text-xs" style="color:var(--pm-muted)">Roles / <span style="color:var(--pm-ink)">Create</span></p>
        </div>
    </div>

    <div class="max-w-2xl bg-white rounded-xl border p-6" style="border-color:var(--pm-stroke)">
        <h2 class="text-base font-semibold mb-5" style="color:var(--pm-ink)">Role Details</h2>

        <form method="POST" action="{{ route('permission-manager.roles.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)"
                    placeholder="e.g. editor">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Guard Name</label>
                <input type="text" name="guard_name" value="{{ old('guard_name', config('permission-manager.guard', 'web')) }}"
                    class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Description <span class="font-normal" style="color:var(--pm-muted)">(optional)</span></label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-lg border text-sm resize-none" style="border-color:var(--pm-stroke)" placeholder="Brief description of this role">{{ old('description') }}</textarea>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Hierarchy Level</label>
                    <input type="number" name="hierarchy_level" value="{{ old('hierarchy_level', 0) }}" min="0"
                        class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
                    <p class="mt-1 text-xs" style="color:var(--pm-muted)">Lower number = higher in hierarchy</p>
                </div>
                <div class="flex flex-col justify-center">
                    <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer" style="border-color:var(--pm-stroke)">
                        <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin') ? 'checked' : '' }}
                            class="w-4 h-4 rounded" style="accent-color:var(--pm-accent)">
                        <div>
                            <span class="block text-sm font-medium" style="color:var(--pm-ink)">Super Admin</span>
                            <span class="block text-xs" style="color:var(--pm-muted)">Bypasses all permission checks</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t" style="border-color:var(--pm-stroke)">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:var(--pm-accent)">
                    Create Role
                </button>
                <a href="{{ route('permission-manager.roles.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
