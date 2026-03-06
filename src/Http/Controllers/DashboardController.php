<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Shakeelnasafian\PermissionManager\Models\AuditLog;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController
{
    public function index(): View
    {
        $userModel = config('permission-manager.user_model');

        $groupCounts = Permission::query()
            ->select('group', DB::raw('count(*) as total'))
            ->groupBy('group')
            ->orderBy('group')
            ->get();

        $auditLogs = [];
        if (config('permission-manager.enable_audit_log', true)) {
            $auditLogs = AuditLog::query()->orderByDesc('created_at')->limit(10)->get();
        }

        return view('permission-manager::dashboard.index', [
            'totalRoles' => Role::count(),
            'totalPermissions' => Permission::count(),
            'totalUsers' => $userModel::query()->count(),
            'groupCounts' => $groupCounts,
            'auditLogs' => $auditLogs,
        ]);
    }
}
