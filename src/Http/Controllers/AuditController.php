<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Shakeelnasafian\PermissionManager\Models\AuditLog;

class AuditController
{
    public function index(Request $request): View
    {
        $query = AuditLog::query()->orderByDesc('created_at');

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->string('entity_type')->toString());
        }

        if ($request->filled('action')) {
            $query->where('action', $request->string('action')->toString());
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }

        $auditLogs = $query
            ->paginate((int) config('permission-manager.pagination', 15))
            ->withQueryString();

        $userModel = config('permission-manager.user_model');
        $users = $userModel::query()->orderBy(config('permission-manager.user_display_field', 'name'))->get();

        return view('permission-manager::audit.index', [
            'auditLogs' => $auditLogs,
            'users' => $users,
            'filters' => $request->only(['entity_type', 'action', 'user_id', 'date_from', 'date_to']),
        ]);
    }
}
