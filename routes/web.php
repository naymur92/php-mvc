<?php

use App\Http\Controllers\HomeController;

// Define routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/test-db-connection', [HomeController::class, 'testDbConnection']);
