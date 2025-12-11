<?php

namespace TalkBox\Server\Controllers;

use TalkBox\Database\Database;

class AuthController
{
    protected $db;

    public function __construct()
    {
        // Initialize database connection (PDO or Mongo depending on config)
        $this->db = Database::getConnection();
    }

    /**
     * Handle login request
     */
    public function login()
    {
        header('Content-Type: application/json');

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$username || !$password) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing username or password'
            ]);
            return;
        }

        // Example SQL query (adjust for your DB type)
        $stmt = $this->db->prepare("SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Start session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'username' => $user['username']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    }

    /**
     * Handle logout request
     */
    public function logout()
    {
        header('Content-Type: application/json');
        session_start();
        session_destroy();

        echo json_encode([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
