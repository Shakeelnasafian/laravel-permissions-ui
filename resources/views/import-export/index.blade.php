@extends('permission-manager::layouts.app')

@section('title', 'Import / Export')

@section('content')
    <div class="mb-6">
        <p class="text-sm" style="color:var(--pm-muted)">Move role and permission data between environments.</p>
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#dbeafe">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold" style="color:var(--pm-ink)">Export Data</h3>
                    <p class="text-xs" style="color:var(--pm-muted)">Download roles and permissions</p>
                </div>
            </div>

            <form method="POST" action="{{ route('permission-manager.import-export.export') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Format</label>
                    <select name="format" class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
                        <option value="json">JSON</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Include</label>
                    <select name="include" class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
                        <option value="both">Roles + Permissions</option>
                        <option value="roles">Roles only</option>
                        <option value="permissions">Permissions only</option>
                    </select>
                </div>

                <button type="submit" class="w-full px-4 py-2.5 rounded-lg text-sm font-medium text-white" style="background:#2563eb">
                    Download Export
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl border p-5" style="border-color:var(--pm-stroke)">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#dcfce7">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#16a34a" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold" style="color:var(--pm-ink)">Import Data</h3>
                    <p class="text-xs" style="color:var(--pm-muted)">Upload a JSON or CSV export to sync</p>
                </div>
            </div>

            <form method="POST" action="{{ route('permission-manager.import-export.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">File</label>
                    <input type="file" name="file"
                        class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:var(--pm-ink)">Format <span class="font-normal" style="color:var(--pm-muted)">(optional)</span></label>
                    <select name="format" class="w-full px-3 py-2 rounded-lg border text-sm" style="border-color:var(--pm-stroke)">
                        <option value="">Auto-detect</option>
                        <option value="json">JSON</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>

                <button type="submit" class="w-full px-4 py-2.5 rounded-lg text-sm font-medium text-white" style="background:#16a34a">
                    Import File
                </button>
            </form>
        </div>
    </div>
@endsection
