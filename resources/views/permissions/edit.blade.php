@extends('permission-manager::layouts.app')

@section('title', 'Edit Permission')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('permission-manager.permissions.index') }}" class="p-1.5 rounded-lg border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <p class="text-xs" style="color:var(--pm-muted)">Permissions / <span style="color:var(--pm-ink)">{{ $permission->name }}</span></p>
    </div>

    <div class="max-w-2xl bg-white rounded-xl border p-6" style="border-color:var(--pm-stroke)">
        <h2 class="text-base font-semibold mb-5" style="color:var(--pm-ink)">Edit Permission</h2>

        <form method="POST" action="{{ route('permission-manager.permissions.update', $permission) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Name</label>
                <input type="text" name="name" value="{{ old('name', $permission->name) }}"
                    class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Guard Name</label>
                <input type="text" name="guard_name" value="{{ old('guard_name', $permission->guard_name) }}"
                    class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Group <span class="font-normal" style="color:var(--pm-muted)">(optional)</span></label>
                <input type="text" name="group" value="{{ old('group', $permission->group) }}" list="permission-groups"
                    class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
                <datalist id="permission-groups">
                    @foreach ($groups as $group)
                        <option value="{{ $group }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t" style="border-color:var(--pm-stroke)">
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:var(--pm-accent)">
                    Save Changes
                </button>
                <a href="{{ route('permission-manager.permissions.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium border" style="border-color:var(--pm-stroke);color:var(--pm-muted)">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
