<?php

namespace Superban\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter as RateLimiterFacade;

class SuperbanMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $requests, $minutes, $banMinutes)
    {
        $key = $this->resolveKey($request);

        if ($this->limiter->tooManyAttempts($key, $requests)) {
            $banKey = 'ban:' . $key;
            Cache::put($banKey, true, $banMinutes);
            $this->limiter->clear($key);

            return response('You are banned.', 403);
        }

        $banKey = 'ban:' . $key;

        if (Cache::get($banKey)) {
            return response('You are banned.', 403);
        }

        return $next($request);
    }

    public function getResolvedKey(Request $request)
    {
        return $this->resolveKey($request);
    }

    protected function resolveKey(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // If the user is authenticated, use their ID as the key
            return 'user:' . $user->id;
        }

        // If the user is not authenticated, use IP address as the key
        return 'ip:' . $request->ip();
    }
}
