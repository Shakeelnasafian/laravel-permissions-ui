<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Shakeelnasafian\PermissionManager\Services\AuditService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionManagerObserver
{
    public function created(Model $model): void
    {
        $this->log('created', $model, [], $this->sanitize($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $old = $this->sanitize($model->getOriginal());
        $new = $this->sanitize($model->getAttributes());

        $this->log('updated', $model, $old, $new);
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model, $this->sanitize($model->getOriginal()), []);
    }

    private function log(string $action, Model $model, array $old, array $new): void
    {
        if (! config('permission-manager.enable_audit_log', true)) {
            return;
        }

        $entityType = $model instanceof Role ? 'role' : ($model instanceof Permission ? 'permission' : 'entity');
        $entityName = $model->getAttribute('name');

        app(AuditService::class)->log(
            $action,
            $entityType,
            $model->getKey(),
            is_string($entityName) ? $entityName : null,
            $old,
            $new
        );
    }

    private function sanitize(array $attributes): array
    {
        return Arr::except($attributes, ['created_at', 'updated_at', 'deleted_at']);
    }
}
