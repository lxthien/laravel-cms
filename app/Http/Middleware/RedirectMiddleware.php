<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't redirect admin requests
        if ($request->is('admin*')) {
            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');
        $fullUrl = $request->fullUrl();

        // Get active redirects, ordered by custom order
        $redirects = Redirect::active()->orderBy('order')->orderBy('id', 'desc')->get();

        foreach ($redirects as $redirect) {
            $match = false;
            $targetUrl = $redirect->destination_url;
            $source = $redirect->source_url;

            // Normalize source (ensure it starts with / if it looks like a path)
            if (!preg_match('/^https?:\/\//', $source) && !str_starts_with($source, '/')) {
                $source = '/' . $source;
            }

            if ($redirect->match_type === 'exact') {
                if ($path === $source || $fullUrl === $source) {
                    $match = true;
                }
            } elseif ($redirect->match_type === 'wildcard') {
                // Convert wildcard * to regex (.*)
                $escapedSource = str_replace(['#', '.', '/'], ['\#', '\.', '\/'], $source);
                $pattern = '#^' . str_replace('*', '(.*)', $escapedSource) . '$#i';

                if (preg_match($pattern, $path, $matches)) {
                    $match = true;
                    // Replace placeholders $1, $2... with capture groups
                    for ($i = 1; $i < count($matches); $i++) {
                        $targetUrl = str_replace('$' . $i, $matches[$i], $targetUrl);
                    }
                }
            } elseif ($redirect->match_type === 'regex') {
                $pattern = '#' . str_replace('#', '\#', $source) . '#i';
                if (preg_match($pattern, $path, $matches)) {
                    $match = true;
                    foreach ($matches as $key => $value) {
                        if (is_string($key) || $key > 0) {
                            $targetUrl = str_replace('$' . $key, $value ?? '', $targetUrl);
                        }
                    }
                }
            }

            if ($match) {
                // Simple infinite loop prevention
                if ($targetUrl === $path || $targetUrl === $fullUrl) {
                    continue;
                }

                // Track analytics
                $redirect->increment('hit_count');
                $redirect->update(['last_hit_at' => now()]);

                return redirect($targetUrl, $redirect->status_code);
            }
        }

        return $next($request);
    }
}
