<?php

namespace App\Http\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            redirect('/');

            // throw new \Exception("Unauthorized", 401);
        }
    }
}
