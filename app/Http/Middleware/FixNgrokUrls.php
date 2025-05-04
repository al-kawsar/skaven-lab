<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixNgrokUrls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Hanya terapkan jika diakses melalui Ngrok dan respons adalah HTML
        if (
            strpos($request->server('HTTP_HOST') ?? '', 'ngrok') !== false &&
            $response->headers->get('Content-Type') == 'text/html; charset=UTF-8'
        ) {

            $content = $response->getContent();

            // Ganti semua URL http:// ke https:// dengan domain Ngrok saat ini
            $ngrokDomain = $request->server('HTTP_HOST');
            $content = str_replace(
                ['http://' . $ngrokDomain],
                ['https://' . $ngrokDomain],
                $content
            );

            $response->setContent($content);
        }

        return $response;
    }
}
