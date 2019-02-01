<?php

namespace Omatech\Imaginator\Middlewares;

use Closure;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;
use Omatech\Imaginator\Exceptions\SecurityException;

class GlideSecurityMiddleware
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
        if (config('imaginator.glide_security_enabled') === true) {
            $path = $request->route('path');
            $params = $request->all();

            try {
                $signkey = config('imaginator.key');
                SignatureFactory::create($signkey)->validateRequest($path, $params);
            } catch (SignatureException $e) {
                throw new SecurityException('Invalid token');
            }
        }

        return $next($request);
    }
}
