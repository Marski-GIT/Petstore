<?php

declare(strict_types=1);

namespace Marski\Middlewares;

use Marski\Exceptions\RequestException;
use Marski\Sessions\AppSession;

final class XCsrf
{
    private static bool $settingXCsrf = true;

    /**
     * @throws RequestException
     */
    public static function verifyToken(string $name, string $requestToken): bool
    {
        self::$settingXCsrf = (bool)getenv('XCSRF');

        if (self::$settingXCsrf) {
            $token = self::getTokens($name);
            $cookieToken = self::getCookieToken($name);

            if (empty($token) || time() > (int)$token['expiry']) {
                self::removeTokens($name);
                return false;
            }

            $formConfirm = hash_equals($token['csrf'], $requestToken);
            $cookieConfirm = hash_equals($token['cookie'], $cookieToken);
            $phpSessionIdConfirm = hash_equals($token['session'], bin2hex($_COOKIE['PHPSESSID']));

            if ($formConfirm && $cookieConfirm && $phpSessionIdConfirm) {
                return true;
            }
            throw  new RequestException();
        }
        return true;
    }

    public static function setNewToken(string $name, int $time = 86400): void
    {

        $rand_token = openssl_random_pseudo_bytes(16);
        $expiry = time() + $time;
        $cookieToken = md5(base64_encode($rand_token));

        AppSession::set('tokens', [$name => [
            'expiry'  => $expiry,
            'csrf'    => bin2hex($rand_token),
            'cookie'  => md5(base64_encode($rand_token)),
            'session' => bin2hex(session_id()),
        ]]);

        $cookieOptions = [
            'expires'  => time() + $time,
            'path'     => '/',
            'domain'   => '',
            'secure'   => true,
            'httponly' => true,
            'SameSite' => 'strict',
        ];

        setcookie(self::makeCookieName($name), $cookieToken, $cookieOptions);
    }

    public static function getToken(string $name): string
    {
        $token = self::getTokens($name);
        return $token['csrf'];
    }

    public static function removeTokens(string $name): bool
    {
        unset($_COOKIE[self::makeCookieName($name)], $_SESSION['tokens'][$name]);
        return true;
    }

    private static function getTokens(string $name): array
    {
        $tokens = AppSession::get('tokens');
        return !empty($tokens[$name]) ? $tokens[$name] : [];
    }

    private static function getCookieToken(string $name): string
    {
        $value = self::makeCookieName($name);
        return !empty($_COOKIE[$value]) ? $_COOKIE[$value] : '';
    }

    private static function makeCookieName(string $name): string
    {
        return 'csrftoken-' . substr(md5($name), 0, 10);
    }
}