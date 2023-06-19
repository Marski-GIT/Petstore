<?php

declare(strict_types=1);

namespace Marski\Controllers;

use Marski\Api\PetStoreApi;
use Marski\Middlewares\XCsrf;
use Marski\Models\PetStoreModel;
use Marski\Rules\RequestPetRules;
use Marski\Views\{Request, Router, View};
use Marski\Exceptions\{AppException, RequestException, ValidationException};

final class PetController extends AbstractController
{
    readonly View $view;
    readonly Router $router;
    readonly PetStoreApi $api;
    readonly bool $isPost;
    readonly array $post;
    readonly array $file;
    private array $message = [];
    private array $errors = [];


    private array $postTemp;

    /**
     * @param View $view The class responsible for downloading view templates.
     * @param Request $request Request processing class.
     * @param Router $router URL processing class.
     * @throws AppException
     */
    public function __construct(View $view, Request $request, Router $router)
    {
        $this->view = $view;
        $this->router = $router;
        $this->action = $router->action();

        $this->api = new PetStoreApi();

        $this->post = $request->post();
        $this->postTemp = $request->post();
        $this->file = $request->files();
        $this->isPost = $request->isMethod('POST');

        $this->view->setHTMLData(['action' => $this->action]);

        $method = $this->defineMethod();

        $this->view->renderHTML('head', 'base/');
        $this->$method();
        $this->view->renderHTML('footer', 'base/');
    }

    /**
     * @return void
     * @throws AppException
     * @description Welcome page.
     */
    private function mainPets(): void
    {
        $this->view->renderHTML('main', 'pet/');
    }


    /**
     * @return void
     * @throws AppException
     * @description Add a new pet to the store.
     */
    private function addNew(): void
    {
        try {
            if ($this->isPost) {
                RequestPetRules::addNew($this->post, $this->file);
                XCsrf::verifyToken('ADD_NEW', $this->post['csrf']);

                $petStore = new PetStoreModel($this->api);
                $result = $petStore->addNew($this->post, $this->file['pet_image']);

                $this->message = $result;
                $this->postTemp = [];
            }
        } catch (RequestException) {
            $this->message['danger'][] = 'Form submission error.';
        } catch (ValidationException $e) {
            $this->errors['message'] = $e->getErrors();
        } finally {

            $this->view->renderHTML('addNew', 'pet/', [
                    'message' => $this->message,
                    'request' => $this->postTemp,
                    'errors'  => $this->errors
                ]
            );
        }
    }

    /**
     * @return void
     * @throws AppException
     * @description Update an existing pet.
     */
    private function updateExisting(): void
    {
        try {
            if ($this->isPost) {
                RequestPetRules::updateExisting($this->post, $this->file);
                XCsrf::verifyToken('UPDATE_EXISTING', $this->post['csrf']);

                $petStore = new PetStoreModel($this->api);
                $result = $petStore->updateExisting($this->post, $this->file['pet_image']);

                $this->message = $result;
                $this->postTemp = [];
            }
        } catch (RequestException) {
            $this->message['danger'][] = 'Form submission error.';
        } catch (ValidationException $e) {
            $this->errors['message'] = $e->getErrors();
        } finally {
            $this->view->renderHTML('updateExisting', 'pet/', [
                    'message' => $this->message,
                    'request' => $this->postTemp,
                    'errors'  => $this->errors
                ]
            );
        }
    }

    /**
     * @return void
     * @throws AppException
     * @description Finds Pets by status.
     */
    private function findsPetsStatus(): void
    {
        $list = [];

        try {
            if ($this->isPost) {
                RequestPetRules::findsPetsStatus($this->post);
                XCsrf::verifyToken('FINDS_BY_STATUS', $this->post['csrf']);

                $petStore = new PetStoreModel($this->api);
                $result = $petStore->findsPetsStatus($this->post);

                $this->message = $result['message'];
                $list = $result['list'];
            }
        } catch (RequestException) {
            $this->message['danger'][] = 'Form submission error.';
        } finally {
            $this->view->renderHTML('findsPetsStatus', 'pet/', [
                    'message' => $this->message,
                    'list'    => $list,
                    'request' => $this->post,
                ]
            );
        }
    }

    /**
     * @return void
     * @throws AppException
     * @description Find pet by ID.
     */
    private function findPetID(): void
    {
        $list = [];

        try {
            if ($this->isPost) {
                RequestPetRules::findPetID($this->post);
                XCsrf::verifyToken('FIND_PET_ID', $this->post['csrf']);

                $petStore = new PetStoreModel($this->api);
                $result = $petStore->findPetID($this->post);

                $this->message = $result['message'];
                $list = $result['list'];
                $this->postTemp = [];
            }
        } catch (RequestException) {
            $this->message['danger'][] = 'Form submission error.';
        } catch (ValidationException $e) {
            $this->errors['message'] = $e->getErrors();
        } finally {
            $this->view->renderHTML('findPetID', 'pet/', [
                    'message' => $this->message,
                    'list'    => $list,
                    'request' => $this->postTemp,
                    'errors'  => $this->errors

                ]
            );
        }
    }

    /**
     * @return void
     * @throws AppException
     * @description Updates a pet in the store with form data.
     */
    private function updatesPetStoreFormData(): void
    {
        try {
            if ($this->isPost) {
                RequestPetRules::updatesPetStoreFormData($this->post);
                XCsrf::verifyToken('UPDATE_PET_STORE_FORM_DATA', $this->post['csrf']);

                $petStore = new PetStoreModel($this->api);
                $result = $petStore->updatesPetStoreFormData($this->post);

                $this->message = $result;
                $this->postTemp = [];
            }
        } catch (RequestException) {
            $this->message['danger'][] = 'Form submission error.';
        } catch (ValidationException $e) {
            $this->errors['message'] = $e->getErrors();
        } finally {
            $this->view->renderHTML('updatesPetStoreFormData', 'pet/', [
                    'message' => $this->message,
                    'request' => $this->postTemp,
                    'errors'  => $this->errors
                ]
            );
        }
    }

    /**
     * @return void
     * @throws AppException
     * @description Deletes a pet.
     */
    private function deletesPet(): void
    {
        try {
            if ($this->isPost) {
                RequestPetRules::deletesPet($this->post);
                XCsrf::verifyToken('DELETE_PET', $this->post['csrf']);

                $petStore = new PetStoreModel($this->api);
                $result = $petStore->deletesPet($this->post);

                $this->message = $result;
                $this->postTemp = [];
            }
        } catch (RequestException) {
            $this->message['danger'][] = 'Form submission error.';
        } catch (ValidationException $e) {
            $this->errors['message'] = $e->getErrors();
        } finally {
            $this->view->renderHTML('deletesPet', 'pet/', [
                    'message' => $this->message,
                    'request' => $this->postTemp,
                    'errors'  => $this->errors
                ]
            );
        }
    }
}