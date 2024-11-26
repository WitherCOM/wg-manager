<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header('Api-Key'))
        {
            abort(401);
        }
        if (ApiKey::where('api_key','=', $request->header('Api-Key'))->where('expires_at','>=',Carbon::now())->count() === 0)
        {
            abort(403);
        }
        return $next($request);
    }
}
