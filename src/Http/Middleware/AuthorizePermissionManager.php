<?php

declare(strict_types=1);

namespace Shakeelnasafian\PermissionManager\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AuthorizePermissionManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            abort(403);
        }

        $gate = config('permission-manager.access_gate');
        if ($gate) {
            Gate::authorize($gate);
        }

        return $next($request);
    }
}
