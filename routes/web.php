<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shakeelnasafian\PermissionManager\Http\Controllers\AuditController;
use Shakeelnasafian\PermissionManager\Http\Controllers\DashboardController;
use Shakeelnasafian\PermissionManager\Http\Controllers\ImportExportController;
use Shakeelnasafian\PermissionManager\Http\Controllers\PermissionController;
use Shakeelnasafian\PermissionManager\Http\Controllers\RoleController;
use Shakeelnasafian\PermissionManager\Http\Controllers\UserController;
use Shakeelnasafian\PermissionManager\Http\Middleware\AuthorizePermissionManager;

Route::group([
    'prefix' => config('permission-manager.route_prefix', 'permission-manager'),
    'middleware' => array_merge(
        config('permission-manager.route_middleware', ['web', 'auth']),
        [AuthorizePermissionManager::class]
    ),
    'as' => 'permission-manager.',
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('/roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.sync-permissions');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/roles', [UserController::class, 'syncRoles'])->name('users.sync-roles');
    Route::post('/users/{user}/permissions', [UserController::class, 'syncPermissions'])->name('users.sync-permissions');

    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');

    Route::get('/import-export', [ImportExportController::class, 'index'])->name('import-export.index');
    Route::post('/export', [ImportExportController::class, 'export'])->name('import-export.export');
    Route::post('/import', [ImportExportController::class, 'import'])->name('import-export.import');
});
