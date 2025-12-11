<?php

namespace TalkBox\Server;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $users;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
        echo "TalkBox WebSocket Server started...\n";
    }

    // When a new connection opens
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection: ({$conn->resourceId})\n";
    }

    // When a message is received
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if (!$data || !isset($data['type'])) {
            echo "Invalid message received.\n";
            return;
        }

        switch ($data['type']) {
            case 'join':
                $username = $data['user'] ?? 'Guest' . $from->resourceId;
                $this->users[$from->resourceId] = $username;
                echo "User joined: {$username}\n";

                // Broadcast updated user list
                $this->broadcast([
                    'type' => 'userlist',
                    'users' => array_values($this->users)
                ]);

                // Notify others
                $this->broadcast([
                    'type' => 'join',
                    'user' => $username
                ], $from);
                break;

            case 'message':
                $username = $this->users[$from->resourceId] ?? 'Unknown';
                $text = $data['text'] ?? '';

                echo "Message from {$username}: {$text}\n";

                $this->broadcast([
                    'type' => 'message',
                    'user' => $username,
                    'text' => $text
                ]);
                break;
        }
    }

    // When a connection closes
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        if (isset($this->users[$conn->resourceId])) {
            $username = $this->users[$conn->resourceId];
            unset($this->users[$conn->resourceId]);

            echo "Connection {$conn->resourceId} closed ({$username}).\n";

            // Broadcast updated user list
            $this->broadcast([
                'type' => 'userlist',
                'users' => array_values($this->users)
            ]);

            // Notify others
            $this->broadcast([
                'type' => 'leave',
                'user' => $username
            ]);
        }
    }

    // On error
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    // Helper: broadcast to all clients
    protected function broadcast(array $message, ConnectionInterface $exclude = null)
    {
        $encoded = json_encode($message);

        foreach ($this->clients as $client) {
            if ($exclude !== null && $client === $exclude) {
                continue;
            }
            $client->send($encoded);
        }
    }
}
