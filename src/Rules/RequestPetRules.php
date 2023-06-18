<?php

declare(strict_types=1);

namespace Marski\Rules;

use Marski\Exceptions\RequestException;

final readonly class RequestPetRules
{
    /**
     * @param array $request
     * @return void
     * @throws RequestException
     */
    public static function addNew(array $request, array $file): void
    {
        $rulesKeys = ['pet_category', 'pet_name', 'pet_tag', 'pet_photo_url', 'csrf'];
        $requestKeys = array_keys($request);

        $evenly = $requestKeys === $rulesKeys;

        if (!$evenly || !array_key_exists('pet_image', $file)) {
            throw new RequestException();
        }
    }

    /**
     * @param array $request
     * @return void
     * @throws RequestException
     */
    public static function updateExisting(array $request, array $file): void
    {
        $rulesKeys = ['pet_id', 'pet_category', 'pet_name', 'pet_tag', 'pet_photo_url', 'pet_status', 'csrf'];
        $requestKeys = array_keys($request);

        $evenly = $requestKeys === $rulesKeys;

        if (!$evenly || !array_key_exists('pet_image', $file)) {
            throw new RequestException();
        }
    }

    /**
     * @param array $request
     * @return void
     * @throws RequestException
     */
    public static function findsPetsStatus(array $request): void
    {
        $rulesKeys = ['pet_status', 'csrf'];
        $requestKeys = array_keys($request);

        $evenly = $requestKeys === $rulesKeys;

        if (!$evenly) {
            throw new RequestException();
        }
    }

    /**
     * @param array $request
     * @return void
     * @throws RequestException
     */
    public static function findPetID(array $request): void
    {
        $rulesKeys = ['pet_id', 'csrf'];
        $requestKeys = array_keys($request);

        $evenly = $requestKeys === $rulesKeys;

        if (!$evenly) {
            throw new RequestException();
        }
    }

    /**
     * @param array $request
     * @return void
     * @throws RequestException
     */
    public static function updatesPetStoreFormData(mixed $request): void
    {
        $rulesKeys = ['pet_id', 'pet_category', 'pet_name', 'pet_status', 'csrf'];
        $requestKeys = array_keys($request);

        $evenly = $requestKeys === $rulesKeys;

        if (!$evenly) {
            throw new RequestException();
        }
    }


    /**
     * @param array $request
     * @return void
     * @throws RequestException
     */
    public static function deletesPet(array $request): void
    {
        $rulesKeys = ['pet_id', 'csrf'];
        $requestKeys = array_keys($request);

        $evenly = $requestKeys === $rulesKeys;

        if (!$evenly) {
            throw new RequestException();
        }
    }
}