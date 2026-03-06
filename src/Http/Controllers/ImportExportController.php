<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Shakeelnasafian\PermissionManager\Services\ImportExportService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportController
{
    public function index(): View
    {
        return view('permission-manager::import-export.index');
    }

    public function export(Request $request, ImportExportService $service): StreamedResponse
    {
        $validated = $request->validate([
            'format' => ['required', 'in:json,csv'],
            'include' => ['required', 'in:roles,permissions,both'],
        ]);

        $format = $validated['format'];
        $include = $validated['include'];

        if ($format === 'json') {
            $data = $service->buildExportData($include);
            $filename = 'permission-manager-export.json';

            return response()->streamDownload(function () use ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }, $filename, ['Content-Type' => 'application/json']);
        }

        $rows = $service->buildCsvRows($include);
        $filename = 'permission-manager-export.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function import(Request $request, ImportExportService $service): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file'],
            'format' => ['nullable', 'in:json,csv'],
        ]);

        $file = $request->file('file');
        $format = strtolower($validated['format'] ?? $file->getClientOriginalExtension());

        $content = file_get_contents($file->getRealPath());
        if ($content === false) {
            return redirect()->back()->with('error', 'Unable to read the uploaded file.');
        }

        if ($format === 'json') {
            $payload = json_decode($content, true);
            if (! is_array($payload)) {
                return redirect()->back()->with('error', 'Invalid JSON file.');
            }
        } elseif ($format === 'csv') {
            $payload = $service->parseCsv($content);
        } else {
            return redirect()->back()->with('error', 'Unsupported file format.');
        }

        $results = $service->import($payload);

        return redirect()
            ->route('permission-manager.import-export.index')
            ->with('success', sprintf(
                'Import complete: %d roles created, %d roles updated, %d permissions created, %d permissions updated.',
                $results['roles_created'],
                $results['roles_updated'],
                $results['permissions_created'],
                $results['permissions_updated']
            ));
    }
}
