<?php

declare(strict_types=1);

namespace Marski\Rules;

use Marski\Exceptions\ValidationException;

final class ValidationPet
{
    private const REGEX_PET_ID = "/^[+]?\d+$/";
    private const REGEX_PET_NAME = "`~@#$%^&*()_+-={}|[]\:;'<>?,./\"";
    private const REGEX_PET_TAG = "`~@#$%^&*()_+-={}|[]\:;'<>?./\"";
    private const REGEX_PET_CATEGORY = "`~@#$%^&*()_+-={}|[]\:;'<>?,./\"";
    private const EXTENSION_RULES = ['.bmp', '.jpg', '.jpeg', '.png'];

    private static array $message = [];

    /**
     * @param string $petId
     * @return bool Return false if matched.
     */
    public static function id(string $petId): bool
    {
        if (empty($petId) || !preg_match(self::REGEX_PET_ID, $petId)) {
            self::$message[] = 'pet_id';
            return false;
        }
        return true;
    }

    /**
     * @param string $category
     * @return bool Return false if matched.
     */
    public static function category(string $category): bool
    {
        if (empty($category) || !!strpbrk($category, self::REGEX_PET_CATEGORY) || (2 > strlen($category))) {
            self::$message[] = 'pet_category';
            return false;
        }
        return true;
    }

    /**
     * @param string $petName
     * @return bool Return false if matched.
     */
    public static function name(string $petName): bool
    {
        if (empty($petName) || !!strpbrk($petName, self::REGEX_PET_NAME) || (2 > strlen($petName))) {
            self::$message[] = 'pet_name';
            return false;
        }
        return true;
    }

    /**
     * @param string $tag
     * @return bool Return false if matched.
     */
    public static function tag(string $tag): bool
    {
        if (empty($tag) || !!strpbrk($tag, self::REGEX_PET_TAG) || (2 > strlen($tag))) {
            self::$message[] = 'pet_tag';
            return false;
        }
        return true;
    }

    /**
     * @param string $url
     * @return bool Return false if matched.
     */
    public static function url(string $url): bool
    {
        $arrayKeyExist = false;

        if (!empty($url)) {
            $tempUrl = explode(', ', $url);

            foreach ($tempUrl as $link) {

                $arrayKeyExist = array_key_exists('pet_photo_url', self::$message);
                $extensions = preg_split('/\./i', $url);
                $extensions = '.' . end($extensions);

                if ((!filter_var($link, FILTER_VALIDATE_URL) || !in_array($extensions, self::EXTENSION_RULES)) && !$arrayKeyExist) {
                    self::$message[] = 'pet_photo_url';
                }
            }
        }

        if ($arrayKeyExist) {
            return false;
        }
        return true;
    }

    /**
     * @param array $file
     * @return bool Return false if matched.
     */
    public static function image(array $file): bool
    {
        $errors = [];
        if (!array_key_exists('error', $file)) {

            $temporary_file = $file['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $temporary_file);
            $data = getimagesize($temporary_file);

            if ($file_type != "image/jpeg" || $file_type != "image/png") {
                $errors[] = 1;
            }

            if ($data[0] === 0 || $data[1] == 0) {
                $errors[] = 1;
            }

            if ($file['size'] === 0) {
                $errors[] = 1;
            }

            self::$message[] = count($errors) ? 'pet_photo_file' : null;
        }
        return (bool)count($errors);
    }

    /**
     * @throws ValidationException
     */
    public static function throw(): void
    {
        $exception = new ValidationException();
        $exception->setMessage(self::$message);

        if (count(self::$message)) {
            throw $exception;
        }
    }
}