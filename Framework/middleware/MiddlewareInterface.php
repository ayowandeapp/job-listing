<?php

namespace Framework\Middleware;

interface MiddlewareInterface
{
    public function handle(callable $next);
}