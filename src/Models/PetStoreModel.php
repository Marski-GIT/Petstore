<?php

declare(strict_types=1);

namespace Marski\Models;

use Marski\Api\PetStoreApi;
use Marski\Exceptions\ApiException;
use Marski\Exceptions\ValidationException;
use Marski\Rules\ValidationPet;

final readonly class PetStoreModel
{
    public PetStoreApi $apiPetStore;

    public function __construct(PetStoreApi $api)
    {
        $this->apiPetStore = $api;
    }

    /**
     * @param array $request
     * @param array $requestFile
     * @return array[] Returns the message.
     * @description Add a new pet to the store.
     * @throws ValidationException
     */
    public function addNew(array $request, array $requestFile): array
    {
        ValidationPet::category($request['pet_category']);
        ValidationPet::name($request['pet_name']);
        ValidationPet::tag($request['pet_tag']);
        ValidationPet::url($request['pet_photo_url']);
        ValidationPet::image($requestFile);

        ValidationPet::throw();

        $request['pet_status'] = 'available';

        $data = $this->prepareBody($request);

        try {
            $response = $this->apiPetStore->addNew($data);
            $responseFile = ['message' => ''];

            if (!isset($requestFile['error'])) {
                $responseFile = $this->apiPetStore->image($response['id'], $requestFile);
            }

            return [
                'success' => ['Adding a new pet to the store success.', 'Your pet ID: ' . $response['id'], $responseFile['message']],
            ];

        } catch (ApiException $e) {
            $e = $e->getStatus();
            return [
                'danger' => [$e['reason'], $e['text']]
            ];
        }
    }

    /**
     * @param array $request
     * @param array $requestFile
     * @return array[] Returns the message.
     * @description Update an existing pet.
     * @throws ValidationException
     */
    public function updateExisting(array $request, array $requestFile): array
    {
        ValidationPet::id($request['pet_id']);
        ValidationPet::category($request['pet_category']);
        ValidationPet::name($request['pet_name']);
        ValidationPet::tag($request['pet_tag']);
        ValidationPet::url($request['pet_photo_url']);
        ValidationPet::image($requestFile);

        ValidationPet::throw();

        $data = $this->prepareBody($request);
        try {
            $response = $this->apiPetStore->updateExisting($data);
            $responseFile = ['message' => ''];

            if (!isset($requestFile['error'])) {
                $responseFile = $this->apiPetStore->image($response['id'], $requestFile);
            }

            return [
                'success' => ['Updating an existing pet.', 'Pet ID: ' . $response['id'], $responseFile['message']],
            ];
        } catch (ApiException $e) {
            $e = $e->getStatus();
            return [
                'danger' => [$e['reason'], $e['text']]
            ];
        }
    }

    /**
     * @param array $request
     * @return array|array[] Returns the message.
     * @description Finds Pets by status.
     */
    public function findsPetsStatus(array $request): array
    {
        $data = $request['pet_status'];

        try {
            $response = $this->apiPetStore->findsPetsStatus($data);

            return [
                'message' => [
                    'success' => ['Finds Pets by status: ', 'Number found: ' . count($response)]
                ],
                'list'    => $response
            ];

        } catch (ApiException $e) {
            $e = $e->getStatus();
            return [
                'message' => [
                    'danger' => [$e['reason'], $e['text']]
                ],
                'list'    => []
            ];
        }
    }

    /**
     * @param array $request
     * @return array|array[]
     * @description Find pet by ID.
     * @throws ValidationException
     */
    public function findPetID(array $request): array
    {
        ValidationPet::id($request['pet_id']);

        ValidationPet::throw();

        try {
            $response = $this->apiPetStore->findPetID($request['pet_id']);

            return [
                'message' => [
                    'success' => ['Finds Pets ID: ' . ucfirst($request['pet_id'])]
                ],
                'list'    => $response
            ];

        } catch (ApiException $e) {
            $e = $e->getStatus();
            return [
                'message' => [
                    'info' => [$e['reason']]
                ],
                'list'    => []
            ];
        }
    }

    /**
     * @param array $request
     * @return array[]
     * @description Updates a pet in the store with form data.
     * @throws ValidationException
     */
    public function updatesPetStoreFormData(array $request): array
    {
        ValidationPet::id($request['pet_id']);
        ValidationPet::category($request['pet_category']);
        ValidationPet::name($request['pet_name']);

        ValidationPet::throw();

        try {
            $response = $this->apiPetStore->updatesPetStoreFormData($request);

            return [
                'success' => ['Updating a pet ID: ' . $response['message']]
            ];

        } catch (ApiException $e) {
            $e = $e->getStatus();
            return [
                'info' => [$e['text']]
            ];
        }
    }

    /**
     * @param array $request
     * @return array[]
     * @description Deletes a pet.
     * @throws ValidationException
     */
    public function deletesPet(array $request): array
    {
        ValidationPet::id($request['pet_id']);

        ValidationPet::throw();

        try {
            $response = $this->apiPetStore->deletesPet($request);

            return [
                'success' => ['Deleting a pet with an ID: ' . $response['message']]
            ];

        } catch (ApiException $e) {
            $e = $e->getStatus();
            return [
                'info' => [$e['text']]
            ];
        }
    }

    /**
     * @param array $request
     * @return array
     * @description Preparation of the resource for the API.
     */
    private function prepareBody(array $request): array
    {
        $tags = [];
        $tagsExplode = explode(',', $request['pet_tag']);

        foreach ($tagsExplode as $key => $tag) {
            $tags[] = [
                'id'   => $key,
                'name' => $tag
            ];
        }

        return [
            'id'        => $request['pet_id'] ?? 0,
            'category'  => [
                'id'   => 0,
                'name' => $request['pet_category']
            ],
            'name'      => $request['pet_name'],
            'photoUrls' => [$request['pet_photo_url']],
            'tags'      => $tags,
            'status'    => $request['pet_status']
        ];
    }


}