<?php

namespace TalkBox\Helpers;

class Utils
{
    /**
     * Send a JSON response and exit
     */
    public static function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Sanitize input string
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Start a session safely
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool
    {
        self::startSession();
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current logged-in username
     */
    public static function currentUsername(): ?string
    {
        self::startSession();
        return $_SESSION['username'] ?? null;
    }

    /**
     * Get current logged-in user ID
     */
    public static function currentUserId(): ?int
    {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Debug helper: log to file
     */
    public static function log(string $message, string $file = __DIR__ . '/../../logs/app.log'): void
    {
        $date = date('Y-m-d H:i:s');
        file_put_contents($file, "[{$date}] {$message}\n", FILE_APPEND);
    }
}
