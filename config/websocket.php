<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WebSocket Server Configuration
    |--------------------------------------------------------------------------
    */

    // Host/IP address where the WebSocket server runs
    'host' => '127.0.0.1',

    // Port number for the WebSocket server
    'port' => 8080,

    // Protocol (ws or wss for secure)
    'protocol' => 'ws',

    // Maximum number of simultaneous connections
    'max_connections' => 100,

    // Whether to enable debug logging
    'debug' => true,
];
