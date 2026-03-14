@if ($paginator->hasPages())
    <div class="mt-5 flex flex-wrap items-center justify-between gap-3 px-1 text-sm">
        <p style="color:var(--pm-muted)">
            Showing
            <span class="font-medium" style="color:var(--pm-ink)">{{ $paginator->firstItem() }}</span>
            &ndash;
            <span class="font-medium" style="color:var(--pm-ink)">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-medium" style="color:var(--pm-ink)">{{ $paginator->total() }}</span>
        </p>
        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg text-xs font-medium" style="border:1px solid var(--pm-stroke);color:var(--pm-muted);cursor:default">&larr; Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-medium" style="border:1px solid var(--pm-stroke);color:var(--pm-ink)">&larr; Prev</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-medium" style="border:1px solid var(--pm-stroke);color:var(--pm-ink)">Next &rarr;</a>
            @else
                <span class="px-3 py-1.5 rounded-lg text-xs font-medium" style="border:1px solid var(--pm-stroke);color:var(--pm-muted);cursor:default">Next &rarr;</span>
            @endif
        </div>
    </div>
@endif
