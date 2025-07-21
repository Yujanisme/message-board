<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class UserLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::channel('user')->info('User Request',[
            'url' => $request->fullurl(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'request' => $request->all(),
        ]);

        $response = $next($request);

        Log::channel('user')->info('Response', [
            'status' => $response->getStatusCode(),
            'Response' => $this->getRespnseContent($response),
        ]);

        return $response;
    }

    function getRespnseContent(Response $response) 
    {
        $contentType = $response->headers->get('Content-Type');

        if($contentType && str_contains($contentType,'application/json')) {
            $content = $response->getContent();
            return json_decode($content);
        }

        return 'Non-Json Response.';
    }
}
