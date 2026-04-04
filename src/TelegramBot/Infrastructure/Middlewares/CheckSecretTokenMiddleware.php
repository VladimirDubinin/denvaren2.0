<?php

declare(strict_types=1);

namespace App\TelegramBot\Infrastructure\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSecretTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('x-telegram-bot-api-secret-token');
        if ($token !== config('telegram.secret_token')) {
            abort(Response::HTTP_UNAUTHORIZED, Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
        }

        return $next($request);
    }
}
