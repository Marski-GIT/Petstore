<?php

declare(strict_types=1);

namespace Marski\Sessions;

final class AppSession
{
    private static int $count;

    public static function run(): void
    {
        if (!self::isSessionStarted()) {
            session_start();
            self::bind();
        }
    }

    public static function get(string $key): mixed
    {
        return $_SESSION[$key];
    }

    public static function set($key, mixed $params = ''): object|bool
    {
        self::$count = count($_SESSION);

        if (is_object($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        $_SESSION[$key] = $params;

        if (count($_SESSION) > self::$count) {
            return true;
        }
        return false;
    }

    private static function bind(): void
    {
        $params = self::getDefaultParams();

        foreach ($params as $key => $value) {
            if (!isset($_SESSION[$key])) {
                $_SESSION[$key] = $value;
            }
        }
    }

    private static function isSessionStarted(): bool
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE;
            } else {
                return !(session_id() === '');
            }
        }
        return false;
    }

    private static function getDefaultParams(): array
    {
        return [
            'tokens' => null,
        ];
    }

}