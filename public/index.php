<?php

use App\Core\Request;
use App\Core\Router;

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH .  'vendor/autoload.php';


// routing part
$router = new Router();
$request = new Request();

// Load routes
require BASE_PATH . 'routes/web.php';

// Resolve the request and send the response
try {
    echo $router->resolve($request);
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo $e->getMessage();
}
