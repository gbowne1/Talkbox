<?php

namespace TalkBox\Models;

use TalkBox\Database\Database;
use PDO;

class Room
{
    public int $id;
    public string $name;
    public string $createdAt;

    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Create a new room
     */
    public function create(string $name): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO rooms (name, created_at)
             VALUES (:name, NOW())"
        );
        $stmt->execute([':name' => $name]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Find a room by ID
     */
    public function findById(int $id): ?self
    {
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->map($data) : null;
    }

    /**
     * Find a room by name
     */
    public function findByName(string $name): ?self
    {
        $stmt = $this->db->prepare("SELECT * FROM rooms WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->map($data) : null;
    }

    /**
     * Get all rooms
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM rooms ORDER BY created_at ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Map database row to Room object
     */
    protected function map(array $data): self
    {
        $room = new self();
        $room->id = (int)$data['id'];
        $room->name = $data['name'];
        $room->createdAt = $data['created_at'];
        return $room;
    }
}
