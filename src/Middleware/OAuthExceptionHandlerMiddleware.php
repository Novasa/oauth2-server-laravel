<?php

namespace LucaDegasperi\OAuth2Server\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Routing\Middleware;
use League\OAuth2\Server\Exception\OAuthException;

/*
* OAuthExceptionHandlerMiddleware
*/
class OAuthExceptionHandlerMiddleware implements Middleware
{
    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);
            // Was an exception thrown? If so and available catch in our middleware
            if (isset($response->exception) && $response->exception) {
                throw $response->exception;
            }

            return $response;
        } catch (OAuthException $e) {
            $data = [
                'error' => $e->errorType,
                'error_description' => $e->getMessage(),
            ];

            return new JsonResponse($data, $e->httpStatusCode, $e->getHttpHeaders());
        }
    }
}
