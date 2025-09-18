<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveCompany
{
    /**
     * This middleware assumes the user is authenticated.
     * It will resolve the active company and attach it to the request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request); // leave auth middleware to block
        }

        $activeCompany = null;
        // If request contains header/company_id (API) prefer that (optional)
        if ($request->header('X-Company-Id')) {
            $companyId = (int)$request->header('X-Company-Id');
            $activeCompany = $user->companies()->where('id', $companyId)->first();
            if (! $activeCompany) {
                return response()->json(['message' => 'Company not found or not owned by user'], Response::HTTP_FORBIDDEN);
            }
        } else {
            $activeCompany = $user->activeCompany()->first();
        }

        // attach to request attributes to be easily retrieved
        $request->attributes->set('current_company', $activeCompany);
        // allow controllers to use $user->currentCompany()
        return $next($request);
    }
}
