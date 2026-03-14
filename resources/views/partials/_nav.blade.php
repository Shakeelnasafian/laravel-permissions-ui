@php
    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'permission-manager.dashboard',
            'pattern' => 'permission-manager.dashboard',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
        ],
        [
            'label' => 'Roles',
            'route' => 'permission-manager.roles.index',
            'pattern' => 'permission-manager.roles.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5.916-3.5M9 20H4v-2a4 4 0 015.916-3.5M15 11a4 4 0 11-8 0 4 4 0 018 0zm6 9v-2a4 4 0 00-3-3.87M3 18v-2a4 4 0 013-3.87"/></svg>',
        ],
        [
            'label' => 'Permissions',
            'route' => 'permission-manager.permissions.index',
            'pattern' => 'permission-manager.permissions.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-3.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>',
        ],
        [
            'label' => 'Users',
            'route' => 'permission-manager.users.index',
            'pattern' => 'permission-manager.users.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
        ],
        [
            'label' => 'Audit Log',
            'route' => 'permission-manager.audit.index',
            'pattern' => 'permission-manager.audit.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>',
        ],
        [
            'label' => 'Import / Export',
            'route' => 'permission-manager.import-export.index',
            'pattern' => 'permission-manager.import-export.*',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>',
        ],
    ];
@endphp

<aside x-data="{ open: false }" class="bg-white border-b border-[var(--pm-stroke)] lg:border-b-0 lg:border-r lg:w-56 lg:flex-shrink-0 lg:min-h-screen lg:flex lg:flex-col">
    <div class="flex items-center justify-between px-4 py-4 border-b border-[var(--pm-stroke)]">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:var(--pm-accent)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold leading-tight" style="color:var(--pm-ink)">Permission</p>
                <p class="text-xs leading-tight" style="color:var(--pm-muted)">Manager</p>
            </div>
        </div>
        <button
            type="button"
            class="rounded-md p-1.5 lg:hidden"
            style="border:1px solid var(--pm-stroke);color:var(--pm-muted)"
            @click="open = !open"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <nav :class="open ? 'block' : 'hidden'" class="px-3 py-3 lg:block lg:flex-1">
        <ul class="space-y-0.5">
            @foreach ($navItems as $item)
                @php $active = request()->routeIs($item['pattern']); @endphp
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                        style="{{ $active ? 'background:var(--pm-accent);color:#fff' : 'color:var(--pm-muted)' }}"
                        onmouseover="{{ $active ? '' : "this.style.background='#f8fafc';this.style.color='var(--pm-ink)'" }}"
                        onmouseout="{{ $active ? '' : "this.style.background='';this.style.color='var(--pm-muted)'" }}"
                    >
                        {!! $item['icon'] !!}
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
