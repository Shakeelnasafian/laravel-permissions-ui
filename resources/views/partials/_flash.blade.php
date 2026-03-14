@if (session('success'))
    <div class="mb-5 flex items-center gap-3 rounded-lg border px-4 py-3 text-sm" style="background:#f0fdf4;border-color:#bbf7d0;color:#15803d">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-5 flex items-center gap-3 rounded-lg border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#dc2626">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-5 rounded-lg border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#dc2626">
        <p class="font-semibold">Please fix the following errors:</p>
        <ul class="mt-2 list-disc pl-5 space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
