<?php

namespace TalkBox\Models;

use TalkBox\Database\Database;
use PDO;

class Message
{
    public int $id;
    public int $userId;
    public string $text;
    public string $createdAt;

    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Save a new message
     */
    public function create(int $userId, string $text): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO messages (user_id, text, created_at)
             VALUES (:user_id, :text, NOW())"
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':text'    => $text
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Get recent messages
     */
    public function getRecent(int $limit = 50): array
    {
        $stmt = $this->db->prepare(
            "SELECT m.id, m.text, m.created_at, u.username
             FROM messages m
             JOIN users u ON m.user_id = u.id
             ORDER BY m.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get messages by user
     */
    public function getByUser(int $userId, int $limit = 50): array
    {
        $stmt = $this->db->prepare(
            "SELECT m.id, m.text, m.created_at, u.username
             FROM messages m
             JOIN users u ON m.user_id = u.id
             WHERE m.user_id = :user_id
             ORDER BY m.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Map database row to Message object
     */
    protected function map(array $data): self
    {
        $msg = new self();
        $msg->id = (int)$data['id'];
        $msg->userId = (int)$data['user_id'];
        $msg->text = $data['text'];
        $msg->createdAt = $data['created_at'];
        return $msg;
    }
}
