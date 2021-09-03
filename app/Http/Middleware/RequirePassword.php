<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RequirePassword as ParentRequirePassword;
use Illuminate\Http\Request;

class RequirePassword extends ParentRequirePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if ($this->shouldConfirmPassword($request)) {
            if ($request->expectsJson()) {
                return $this->responseFactory->json([
                    'message' => 'Password confirmation required.',
                ], 423);
            }

            $confirmationRoute = 'password.confirm';
            if($request->is('admin') || $request->is('admin/*')){
                $confirmationRoute = 'admin.password.confirm';
            }

            return $this->responseFactory->redirectGuest(
                $this->urlGenerator->route($redirectToRoute ?? $confirmationRoute)
            );
        }

        return $next($request);
    }
}
