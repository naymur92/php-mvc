<?php

namespace App\Core;

class CSRF
{
    /**
     * CSRF token generate
     *
     * @return string
     */
    public static function generateToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Get csrf token
     *
     * @return string|null
     */
    public static function getToken(): string|null
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['csrf_token'] ?? null;
    }

    /**
     * Verify csrf token
     *
     * @param string $token
     * @return boolean
     */
    public static function verifyToken(string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}
