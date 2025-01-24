<?php

namespace App\Core;

class Auth
{
    /**
     * Get authenticated user
     *
     * @return object|null
     */
    public static function user(): object|null
    {
        $user = Session::get('user');
        return $user ? (object) $user : null;
    }
}
