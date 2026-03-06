<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Services;

use Illuminate\Support\Carbon;
use Shakeelnasafian\PermissionManager\Models\AuditLog;

class AuditService
{
    public function log(
        string $action,
        string $entityType,
        $entityId,
        ?string $entityName,
        array $oldValues = [],
        array $newValues = []
    ): void {
        if (! config('permission-manager.enable_audit_log', true)) {
            return;
        }

        $request = request();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'entity_name' => $entityName,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'created_at' => Carbon::now(),
        ]);
    }
}
