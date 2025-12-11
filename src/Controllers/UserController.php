<?php

namespace TalkBox\Controllers;

use TalkBox\Database\Database;

class UserController
{
    protected $db;

    public function __construct()
    {
        // Initialize database connection
        $this->db = Database::getConnection();
    }

    /**
     * Get all users
     * Example: GET /users
     */
    public function getAllUsers()
    {
        header('Content-Type: application/json');

        try {
            $stmt = $this->db->query(
                "SELECT id, username, status, last_seen
                 FROM users
                 ORDER BY username ASC"
            );
            $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get a single user by ID
     * Example: GET /users?id=123
     */
    public function getUser($id)
    {
        header('Content-Type: application/json');

        try {
            $stmt = $this->db->prepare(
                "SELECT id, username, status, last_seen
                 FROM users
                 WHERE id = :id"
            );
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode([
                    'success' => true,
                    'user' => $user
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update user status (online/offline)
     * Example: POST /users/status
     */
    public function updateStatus($id, $status)
    {
        header('Content-Type: application/json');

        try {
            $stmt = $this->db->prepare(
                "UPDATE users
                 SET status = :status, last_seen = NOW()
                 WHERE id = :id"
            );
            $stmt->execute([
                ':status' => $status,
                ':id' => $id
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Status updated'
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
