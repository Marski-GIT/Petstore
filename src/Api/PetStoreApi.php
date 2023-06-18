<?php

declare(strict_types=1);

namespace Marski\Api;

use CURLFile;
use CurlHandle;
use Marski\Exceptions\ApiException;

final readonly class PetStoreApi
{
    const API_URL = 'https://petstore.swagger.io/v2/pet';
    private false|CurlHandle $curl;

    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * @return array Request body.
     * @throws ApiException
     * @description Add a new pet to the store.
     */
    public function addNew(array $body): array
    {
        $body = json_encode($body);

        curl_setopt($this->curl, CURLOPT_URL, self::API_URL);
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type:application/json'
        ]);

        return $this->response();
    }

    /**
     * @return array Request body.
     * @throws ApiException
     * @description Update an existing pet.
     */
    public function updateExisting(array $body): array
    {
        $body = json_encode($body);

        curl_setopt($this->curl, CURLOPT_URL, self::API_URL);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type:application/json'
        ]);

        return $this->response();
    }

    /**
     * @throws ApiException
     * @description Uploads an image.
     */
    public function image(int $petID, array $files): array
    {
        $file = new CURLFile($files['tmp_name'], $files['type'], $files['name']);
        $data = array('name' => $files['name'], 'file' => $file);

        curl_setopt($this->curl, CURLOPT_URL, self::API_URL . '/' . $petID . '/uploadImage');
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: multipart/form-data'
        ]);

        return $this->response();
    }

    /**
     * @return array Request body.
     * @description Finds Pets by status.
     * @throws ApiException
     */
    public function findsPetsStatus(string $data): array
    {
        curl_setopt($this->curl, CURLOPT_URL, self::API_URL . '/findByStatus?status=' . $data);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type:application/json'
        ]);

        return $this->response();
    }

    /**
     * @return array Returns a single pet.
     * @throws ApiException
     * @description Find pet by ID.
     */
    public function findPetID(mixed $data): array
    {
        curl_setopt($this->curl, CURLOPT_URL, self::API_URL . '/' . $data);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type:application/json'
        ]);

        return $this->response();
    }

    /**
     * @throws ApiException
     * @description Updates a pet in the store with form data.
     */
    public function updatesPetStoreFormData(array $data): array
    {
        $url = http_build_query(
            [
                'name'   => $data['pet_name'],
                'status' => $data['pet_status']
            ]
        );

        curl_setopt($this->curl, CURLOPT_URL, self::API_URL . '/' . $data['pet_id']);
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        return $this->response();

    }

    /**
     * @throws ApiException
     * @description Deletes a pet.
     */
    public function deletesPet(array $data)
    {
        curl_setopt($this->curl, CURLOPT_URL, self::API_URL . '/' . $data['pet_id']);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);

        return $this->response();
    }

    /**
     * @throws ApiException
     */
    private function response()
    {
        $response = curl_exec($this->curl);
        $responseCode = (int)curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        curl_close($this->curl);

        $response = json_decode($response, true);

        if ($responseCode !== 200) {
            throw new ApiException(ucfirst($response['message'] ?? ''), $responseCode);
        }

        return $response;
    }


}
