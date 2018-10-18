<?php

namespace Mtownsend\RequestXml\Middleware;

use Closure;

class XmlRequest
{
    /**
     * Merge the converted xml array into the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge($request->xml());

        return $next($request);
    }
}
