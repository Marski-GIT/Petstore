<?php

declare(strict_types=1);

ini_set("display_errors", 0);

use Marski\Controllers\PetController;
use Marski\Middlewares\{DotEnv};
use Marski\Exceptions\AppException;
use Marski\Sessions\AppSession;
use Marski\Views\{Request, Router, View};

date_default_timezone_set('Europe/Warsaw');
header('Accept-Encoding: gzip, compress, br');
//header('Cache-Control: private, max-age=604800, immutable');

spl_autoload_register(function (string $classNamespace) {
    $path = str_replace(['\\', 'Marski/'], ['/', ''], $classNamespace);
    $path = 'src' . DIRECTORY_SEPARATOR . $path . '.php';

    require_once $path;
});

AppSession::run();

try {

    (new DotEnv(__DIR__ . '/.env'));
    (new PetController(new View(), new Request(), new Router()));

} catch (AppException $e) {
    $e = $e->getStatus();
    $reason = $e['reason'];
    $text = $e['text'];
    echo <<<EOF
    <div class="alert alert-waring" role="alert">
        <p>Application error: $reason</p>
        <p>$text</p>
    </div>
EOF;
} catch (Throwable $e) {
    $e = $e->getMessage();
    echo <<<EOF
    <div class="alert alert-waring" role="alert">
        <p>Fatal Application Error</p>
        <p>$e</p>
    </div>
EOF;
}
