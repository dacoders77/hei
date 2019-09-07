<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckCampaignLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $cid = campaign_from_domain( url('/'), preg_replace('/\/?login\/?$/i','',$request->path()) );

        if(!$cid) abort(404);
        return $next($request);
    }
}
