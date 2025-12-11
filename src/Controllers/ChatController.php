<?php

namespace TalkBox\Controllers;

use TalkBox\Database\Database;

class ChatController
{
    protected $db;

    public function __construct()
    {
        // Initialize database connection
        $this->db = Database::getConnection();
    }

    /**
     * Fetch recent messages
     * Example: GET /chat/messages
     */
    public function getMessages($limit = 50)
    {
        header('Content-Type: application/json');

        try {
            $stmt = $this->db->prepare(
                "SELECT m.id, u.username, m.text, m.created_at
                 FROM messages m
                 JOIN users u ON m.user_id = u.id
                 ORDER BY m.created_at DESC
                 LIMIT :limit"
            );
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            $stmt->execute();

            $messages = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Save a new message
     * Example: POST /chat/send
     */
    public function sendMessage()
    {
        header('Content-Type: application/json');
        session_start();

        $userId = $_SESSION['user_id'] ?? null;
        $text   = $_POST['text'] ?? null;

        if (!$userId || !$text) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing user or message text'
            ]);
            return;
        }

        try {
            $stmt = $this->db->prepare(
                "INSERT INTO messages (user_id, text, created_at)
                 VALUES (:user_id, :text, NOW())"
            );
            $stmt->execute([
                ':user_id' => $userId,
                ':text'    => $text
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Message sent'
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
