<?php

namespace Wefabric\TokenLogin\Http\Middleware;

use Wefabric\TokenLogin\TokenLogin;

class TokenLoginEnabledMiddleware
{

    /**
     * Checkes if the token login is enabled
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|void
     */
    public function handle($request, $next)
    {
        return TokenLogin::make()->enabled() ? $next($request) : abort(403);
    }
}
