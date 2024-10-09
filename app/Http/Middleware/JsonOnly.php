<?php

namespace Itpi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get header accept
        $acceptHeader = $request->header('Accept');
        // Check header accept
        if ($acceptHeader != 'application/json') {
            return response()->json(['message' => 'Content not accepted !'], 406);
        }
        // Return request
        return $next($request);
    }
}
