<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Http\Request\Request;
use App\Http\Routing\HttpErrorsResolver;
use Dotenv\Dotenv;

$dotenv = Dotenv::createMutable(dirname(__DIR__));
$dotenv->load();

try {
    $request = Request::makeFromGlobalRequestVariables($_SERVER);
    $app = new App\App();
    $app->run($request);
} catch (Exception $e) {
    //@todo: log error
    (new HttpErrorsResolver())->handleErrorIfPossible($e);
}
