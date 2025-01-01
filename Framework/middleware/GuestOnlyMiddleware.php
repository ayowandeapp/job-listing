<?php

namespace Framework\Middleware;

use Framework\Session;
class GuestOnlyMiddleware implements MiddlewareInterface
{

    public function isAuthenticated(): bool
    {
        return Session::has('user');

    }
    public function handle(callable $next)
    {
        if ($this->isAuthenticated()) {
            return redirect('/listings');
        }

        //call the controller class and method
        $next();

    }
}