<?php
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TalkBox Chat</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="app">
        <!-- Header -->
        <header>
            <h1>TalkBox</h1>
        </header>

        <!-- Login Section -->
        <section id="login-section">
            <form id="login-form">
                <input type="text" id="username" name="username" placeholder="Enter username" required>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
                <button type="submit">Login</button>
            </form>
        </section>

        <!-- Chat Section -->
        <section id="chat-section" style="display:none;">
            <aside id="user-list">
                <h2>Users Online</h2>
                <ul id="users"></ul>
            </aside>

            <main id="chat-window">
                <div id="messages"></div>
                <form id="message-form">
                    <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
                    <button type="submit">Send</button>
                </form>
            </main>
        </section>
    </div>

    <!-- Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/websocket.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
