<?php

namespace Spp\ProductBuilder\Http\Middleware;

use Spp\ProductBuilder\ProductBuilder;

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
        return resolve(ProductBuilder::class)->authorize($request) ? $next($request) : abort(403);
    }
}
