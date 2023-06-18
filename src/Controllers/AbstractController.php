<?php

declare(strict_types=1);

namespace Marski\Controllers;

class AbstractController
{
    protected string $action;

    /**
     * @description Defining an action method.
     * @return string
     */
    protected function defineMethod(): string
    {
        $defaultAction = getenv('DEFAULT_ACTION');

        $method = $this->action;
        if (!method_exists($this, $method)) {
            $method = $defaultAction;
        }
        return $method;
    }
}