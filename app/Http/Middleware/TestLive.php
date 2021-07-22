<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\LivewireCore\Html\Helper as HtmlHelper;
class TestLive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {


        return $next($request);
    }
}
