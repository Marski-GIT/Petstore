<?php

declare(strict_types=1);

namespace Marski\Views;

use Marski\Exceptions\AppException;
use Marski\Middlewares\Escape;

final class View
{
    private static array $htmlData = [];
    private static array $pageData = [];

    public function __construct()
    {
        self::$htmlData['url'] = getenv('SITE_URL');
    }

    public function setHTMLData(array $params): void
    {
        self::$htmlData = array_merge(self::$htmlData, $params);
        self::$htmlData['title'] = $this->getTitle($params['action']);
    }

    public static function values(): bool|array|string
    {
        return Escape::view(self::$pageData);
    }

    public static function data(): array
    {
        return self::$htmlData;
    }

    /**
     * @throws AppException
     */
    public function renderHTML(string $name, string $path = '', array $pageData = []): void
    {
        self::$pageData = $pageData;
        $path = 'template/' . $path . $name . '.php';

        if (is_file($path)) {
            require_once $path;
        } else {
            throw new AppException('Błąd otwarcia szablonu ' . $name, 400);
        }
    }

    private function getTitle(string $action): string
    {
        return match ($action) {
            'findsPetsStatus'         => 'Finds Pets by status',
            'findPetID'               => 'Find pet by ID',
            'updateExisting'          => 'Update an existing pet',
            'updatesPetStoreFormData' => 'Updates a pet in the store with form data',
            'deletesPet'              => 'Deletes a Pet',
            default                   => 'Add a new pet to the store'
        };
    }
}