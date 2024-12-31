<?php

namespace Framework;

class Session
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::has($key) ? $_SESSION[$key] : $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function clear(string $key): void
    {
        if (self::has($key))
            unset($_SESSION[$key]);
    }

    public static function clearAll(): void
    {
        session_unset();
        session_destroy();
    }


    public static function setFlashMessage(string $key, mixed $value): void
    {
        self::set("flash_$key", $value);
    }
    public static function getFlashMessage(string $key, mixed $default = null): mixed
    {
        $message = self::get("flash_$key", $default);
        self::clear("flash_$key");
        return $message;
    }

}