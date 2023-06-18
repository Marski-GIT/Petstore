<?php

declare(strict_types=1);

namespace Marski\Exceptions;

use Exception;
use Marski\Enums\HttpCode;

final class ApiException extends Exception
{
    public function getStatus(): array
    {
        $code = $this->getCode();

        return [
            'status' => $this->getCode(),
            'reason' => $this->getMessage(),
            'text'   => HttpCode::getText($code),
        ];
    }
}
