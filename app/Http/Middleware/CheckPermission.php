<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserPermission;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
{
    $user = $request->user();

    // ✅ ADMIN BYPASS
    if ($user->role === 'admin') {
        return $next($request);
    }

    $hasPermission = UserPermission::where('user_id', $user->id)
        ->where('permission', $permission)
        ->exists();

    if (!$hasPermission) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    return $next($request);
}
}
