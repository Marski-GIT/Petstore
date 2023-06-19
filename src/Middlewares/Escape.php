<?php

declare(strict_types=1);

namespace Marski\Middlewares;

final class Escape
{
    private static array $exceptions = ['pagination'];

    /**
     * @param mixed $data
     * @return bool|array|string
     * @description Prepared parameters for the view.
     */
    public static function view(mixed $data): bool|array|string
    {
        return self::init($data);
    }

    /**
     * @param mixed $data
     * @return bool|array|string
     * @description Initialization of value preparation.
     */
    private static function init(mixed $data): bool|array|string
    {
        $type = gettype($data);
        return match ($type) {
            'array'  => self::escapeArray($data),
            'string' => self::escapeString($data),
            default  => $data
        };
    }

    /**
     * @param array $params
     * @return array
     * @description Initialization of preparation of values in the table.
     */
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
                'array'  => self::escapeArray($param),
                'string' => self::escapeString($param),
                default  => $param
            };
        }
        return $clearParams;
    }

    /**
     * @param string $data
     * @return string
     * @description Escape in a string.
     */
    private static function escapeString(string $data): string
    {
        $data = trim($data);
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
