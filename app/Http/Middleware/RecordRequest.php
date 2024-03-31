<?php

namespace App\Http\Middleware;

use App\Auth\Services\CreateRequestLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $payload = $request->all();

        app(CreateRequestLogService::class)
            ->setPayload(
                [
                    'source_ip' => $request->ip(),
                    'http_method' => $request->method(),
                    'domain' => $request->root(),
                    'path' => $request->path(),
                    'payload' => json_encode($payload)
                ]
            )
            ->exec();

        return $next($request);
    }
}
