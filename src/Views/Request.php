<?php

declare(strict_types=1);

namespace Marski\Views;

final readonly class Request
{
    private array $get;
    private array $post;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
    }

    /**
     * @param string|array $param
     * @return mixed
     * @description Retrieving POST data.
     *  - Without parameters, the whole array.
     *  - String as key
     *  - Key board
     */
    public function post(string|array $param = []): mixed
    {
        $tempPost = $this->preparePost($this->post);

        if (is_array($param) && !empty($param)) {
            $temp = [];
            foreach ($param as $key) {
                $temp[$key] = array_key_exists($key, $tempPost) ? $tempPost[$key] : null;
            }
            return $temp;
        }

        if (empty($param)) {
            return $tempPost;
        }

        return array_key_exists($param, $tempPost) ? $tempPost[$param] : null;
    }

    /**
     * @return array
     * @description File from the form.
     */
    public function files(): array
    {
        return $_FILES;
    }

    /**
     * @param string $methodName
     * @return bool
     * @description Whether the request method was used.
     */
    public function isMethod(string $methodName): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === $methodName) {
            return true;
        }
        return false;
    }

    /**
     * @param array $request
     * @return array
     * @description Remove whitespace and last comma.
     */
    private function preparePost(array $request): array
    {
        $post = [];

        foreach ($request as $key => $value) {
            if (is_array($value)) {
                $post = $this->preparePost($value);
            }
            $post[$key] = rtrim(trim($value), ',');
        }

        return $post;
    }
}