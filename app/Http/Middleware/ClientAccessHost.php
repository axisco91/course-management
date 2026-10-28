<?php
namespace App\Http\Middleware;

use Closure;

class ClientAccessHost
{
    public function handle($request, Closure $next)
    {
        $domain = config('client_access.domain');
        if ($domain && $request->getHost() === 'accesoclientes.' . strtolower($domain)) {
            abort_unless($request->isMethod('GET') && preg_match('~^public/companies/(search|[1-9][0-9]*)$~D', $request->path()), 404);
        }
        return $next($request);
    }
}
