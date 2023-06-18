<?php

declare(strict_types=1);

namespace Marski\Middlewares;

final class Escape
{
    private static array $exceptions = ['pagination'];

    public static function view(mixed $data): bool|array|string
    {
        return self::init($data);
    }

    private static function init(mixed $data): bool|array|string
    {
        $type = gettype($data);
        return match ($type) {
            'array' => self::escapeArray($data),
            'string' => self::escapeString($data),
            default => $data
        };
    }

    private static function escapeArray(array $params): array
    {
        $clearParams = [];
        foreach ($params as $key => $param) {

            if (in_array($key, self::$exceptions)) {
                $clearParams[$key] = $param;
                continue;
            }

            $type = gettype($param);
            $clearParams[$key] = match ($type) {
                'array' => self::escapeArray($param),
                'string' => self::escapeString($param),
                default => $param
            };
        }
        return $clearParams;
    }

    private static function escapeString(string $data): string
    {
        $data = trim($data);
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
