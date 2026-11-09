<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VaultUnlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        $until = session('vault_until');

        // locked, or the time ran out -> pretend the page doesn't exist
        if (! $until || now()->timestamp > $until) {
            session()->forget('vault_until');
            abort(404);
        }

        // keep it open while you're active
        session(['vault_until' => now()->addMinutes(config('vault.timeout'))->timestamp]);

        return $next($request);
    }
}