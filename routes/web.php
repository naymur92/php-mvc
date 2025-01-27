<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

// Define routes
$router->get('/', [HomeController::class, 'index']);
// $router->get('/test-db-connection', [HomeController::class, 'testDbConnection']);

$router->get('/users', [UserController::class, 'index']);
$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users', [UserController::class, 'store']);
$router->get('/login', [AuthenticationController::class, 'index'])->only(['guest']);
$router->post('/login', [AuthenticationController::class, 'login'])->only(['guest']);
$router->post('/logout', [AuthenticationController::class, 'logout'])->only(['auth']);
