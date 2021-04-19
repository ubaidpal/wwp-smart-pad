<?php

namespace Spp\ItemBuilder\Http\Middleware;

use Spp\ItemBuilder\ItemBuilder;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(ItemBuilder::class)->authorize($request) ? $next($request) : abort(403);
    }
}
