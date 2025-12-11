<?php

namespace TalkBox\Models;

use TalkBox\Database\Database;

class User
{
    public $id;
    public $username;
    public $passwordHash;
    public $status;
    public $lastSeen;

    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Find user by ID
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? $this->map($data) : null;
    }

    /**
     * Find user by username
     */
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? $this->map($data) : null;
    }

    /**
     * Create a new user
     */
    public function create($username, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password_hash, status, last_seen)
             VALUES (:username, :password_hash, 'offline', NOW())"
        );

        $stmt->execute([
            ':username' => $username,
            ':password_hash' => $hash
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Update user status
     */
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET status = :status, last_seen = NOW() WHERE id = :id"
        );
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    /**
     * Map database row to User object
     */
    protected function map(array $data)
    {
        $user = new self();
        $user->id = $data['id'];
        $user->username = $data['username'];
        $user->passwordHash = $data['password_hash'];
        $user->status = $data['status'];
        $user->lastSeen = $data['last_seen'];
        return $user;
    }
}
