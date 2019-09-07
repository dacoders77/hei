<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

class CheckInstall
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
        if( !$this->hasTables() ) {
            if ( Route::getRoutes()->match($request)->getName() !== 'install' ) {
                return redirect('/install');
            }
        }
        return $next($request);
    }

    static function hasTables()
    {
        $tables = ['admins','campaigns','migrations','password_resets','submissions','users'];

        foreach ($tables as $table) {
            if( !Schema::hasTable($table) ) return false;
        }

        return true;
    }
}
