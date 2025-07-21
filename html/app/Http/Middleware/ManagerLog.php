<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ManagerLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Request
        Log::channel('manager')->info('Manager Log', [
            'manaegr_id' => auth()->id(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'request' => $request->all(),
        ]);

        // Response 
        $response = $next($request);

        Log::channel('manager')->info('Response :', [
            'status' => $response->getStatusCode(),
            'response' => $this->getResponseContent($response),
        ]);

        return $response;
    }

    function getResponseContent(Response $response)
    {
        $contentType = $response->headers->get('Content-Type');

        if ($contentType && str_contains($contentType, 'application/json')) {
            $content = $response->getContent();
            return json_decode($content, true) ?? $content;
        }
        
        return 'Non-Json Response';
    }
}
