<?php

declare(strict_types=1);

namespace Marski\Sessions;

final class AppSession
{
    /**
     * @return void
     * @description Check if the session exists or run run.
     */
    public static function run(): void
    {
        if (!self::isSessionStarted()) {
            session_start();
            self::bind();
        }
    }

    /**
     * @param string $key
     * @return mixed
     * @description Get an item from the session table.
     */
    public static function get(string $key): mixed
    {
        return $_SESSION[$key];
    }

    /**
     * @param $key
     * @param mixed $params
     * @return void
     * @description Adding an item to the session.
     */
    public static function set($key, mixed $params = ''): void
    {
        $_SESSION[$key] = $params;
    }

    /**
     * @return void
     * @description Completion of default keys.
     */
    private static function bind(): void
    {
        $params = self::getDefaultParams();

        foreach ($params as $key => $value) {
            if (!isset($_SESSION[$key])) {
                $_SESSION[$key] = $value;
            }
        }
    }

    /**
     * @return bool
     * @description Whether the session is already running.
     */
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

    /**
     * @return null[]
     * @description Array of default session keys
     */
    private static function getDefaultParams(): array
    {
        return [
            'tokens' => null,
        ];
    }

}