<?php

declare(strict_types=1);

namespace Marski\Exceptions;

use Exception;

final class ValidationException extends Exception
{
    private const CSS_STYLE = 'text-danger';
    readonly array $messageValidation;

    public function getErrors(): array
    {
        $message = [];
        foreach ($this->messageValidation as $txt) {
            $message[$txt] = self::CSS_STYLE;
        }
        return $message;
    }

    public function setMessage(array $message): void
    {
        $this->messageValidation = $message;
    }
}