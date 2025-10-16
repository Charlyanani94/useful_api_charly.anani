<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


/** */
use App\Models\Module;
use App\Models\UserModule;
use Illuminate\Support\Facades\Auth;   

/** */


class CheckModuleActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */



    public function handle(Request $request, Closure $next, $moduleId): Response
{
    $userId = Auth::id();

    $userModule = UserModule::where('user_id', $userId)
        ->where('module_id', $moduleId)
        ->first();

    if (! $userModule || ! $userModule->active) {
        return response()->json([
            'error' => 'Module inactive. Please activate this module to use it.'
        ], 403);
    }

    return $next($request);
}   
}
