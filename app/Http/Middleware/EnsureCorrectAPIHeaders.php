<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class EnsureCorrectAPIHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->headers->get('accept') !== 'application/vnd.api+json') {
            return new Response('', 406);
        }

       if ($request->isMethod('POST') || $request->isMethod('PATCH')) {

            $contentType = $request->headers->get('content-type');
            
            if (strpos($contentType, 'application/vnd.api+json') === false && strpos($contentType, 'multipart/form-data') === false) {
                return new Response('', 415);
            }
        }

        return $this->addCorrectContentType($next($request));

    }

    private function addCorrectContentType(BaseResponse $response)
    {
        $response->headers->set('content-type', 'application/vnd.api+json');
        return $response;
    }

}
