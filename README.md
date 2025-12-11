# TalkBox

TalkBox is a lightweight real‑time chat application built in PHP with Ratchet WebSockets and Symfony components. It supports multiple chat rooms, persistent message storage, and REST endpoints for login and user management.

## Features
- Real‑time messaging via WebSockets
- REST API for login, logout, user list, and message history
- Multiple chat rooms/channels
- Secure password hashing and session management
- Database‑agnostic (MySQL, PostgreSQL, SQLite, MongoDB)
- Simple logging (logs/chat.log)

## Project Structure
talkbox/
├── bin/                # Bootstrap scripts
├── config/             # Configuration files
│   ├── database.php
│   ├── websocket.php
├── logs/               # Runtime logs (ignored in Git)
├── src/
│   ├── Controllers/    # Auth, Chat, User controllers
│   ├── Database/       # Database connection manager
│   ├── Helpers/        # Utility functions
│   ├── Models/         # User, Message, Room models
│   └── Server/         # WebSocket & HTTP servers
├── database/
│   ├── migrations/     # SQL schema files
│   └── seeds/          # Demo/test data
└── vendor/             # Composer dependencies (ignored in Git)

## Getting Started
1. Clone the repo
   git clone https://github.com/gbowne1/talkbox.git
   cd talkbox

2. Install dependencies
   composer install

3. Configure database
   Edit config/database.php to match your environment (MySQL, PostgreSQL, SQLite, or MongoDB).

4. Run migrations
   For SQL databases, create tables using the files in database/migrations:
   mysql -u root -p talkbox < database/migrations/001_create_users.sql
   mysql -u root -p talkbox < database/migrations/002_create_messages.sql
   mysql -u root -p talkbox < database/migrations/003_create_rooms.sql

5. Seed demo data
   mysql -u root -p talkbox < database/seeds/demo_data.sql

6. Start servers
   Run the WebSocket server:
   php bin/websocket.php

   Run the HTTP server:
   php bin/http.php

7. Connect frontend
   Point your frontend (websocket.js / app.js) to the host/port defined in config/websocket.php.

## Logging
Chat events are written to logs/chat.log. This file is ignored in Git but useful for debugging.

## Contributing
1. Fork the repo
2. Create a feature branch (git checkout -b feature/my-feature)
3. Commit changes (git commit -m "Add my feature")
4. Push to branch (git push origin feature/my-feature)
5. Open a Pull Request

License
MIT License. See LICENSE file for details.
