<?php

namespace App\Http\Middleware;

class GuestMiddleware
{
    public function handle()
    {
        if (isset($_SESSION['user'])) {
            redirect('/');

            // throw new \Exception("Access denied for authenticated users.", 403);
        }
    }
}
