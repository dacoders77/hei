<?php

namespace App\Http\Middleware;

use Closure;
use Campaign;
use Illuminate\Support\Facades\Auth;

class CampaignPublished
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
        // Get Campaign by URL
        $campaign = Campaign::where([
            ['url','REGEXP','^https?\:\/\/' . preg_replace('/([^a-z0-9])/i','\\\$1',rtrim(\Request::getHost(), '/')) . '\/?$'],
        ])->first();

        if( $campaign && ( $campaign->status !== 0 || Auth::guard('admin')->check() ) ) {
            return $next($request);
        } else {
            abort(404);
        }
    }
}
