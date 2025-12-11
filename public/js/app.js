$(document).ready(function () {
    let socket = null;
    let currentUser = null;

    // Handle login form
    $("#login-form").on("submit", function (e) {
        e.preventDefault();

        const username = $("#username").val().trim();
        const password = $("#password").val().trim();

        if (!username || !password) {
            alert("Please enter both username and password.");
            return;
        }

        // Example AJAX call to authenticate user
        $.ajax({
            url: "/../src/Controllers/AuthController.php",
            method: "POST",
            data: { username, password },
            success: function (response) {
                if (response.success) {
                    currentUser = username;
                    $("#login-section").hide();
                    $("#chat-section").show();

                    // Connect to WebSocket after successful login
                    connectWebSocket(username);
                } else {
                    alert("Login failed: " + response.message);
                }
            },
            error: function () {
                alert("Error connecting to server.");
            }
        });
    });

    // Handle message form
    $("#message-form").on("submit", function (e) {
        e.preventDefault();

        const message = $("#message-input").val().trim();
        if (!message || !socket) return;

        const payload = {
            type: "message",
            user: currentUser,
            text: message
        };

        socket.send(JSON.stringify(payload));
        $("#message-input").val("");
    });

    // Connect to WebSocket server
    function connectWebSocket(username) {
        // Adjust port/host to match your WebSocket server
        socket = new WebSocket("ws://localhost:8080");

        socket.onopen = function () {
            console.log("Connected to TalkBox WebSocket server.");
            // Notify server of new user
            socket.send(JSON.stringify({ type: "join", user: username }));
        };

        socket.onmessage = function (event) {
            const data = JSON.parse(event.data);

            switch (data.type) {
                case "message":
                    addMessage(data.user, data.text);
                    break;
                case "userlist":
                    updateUserList(data.users);
                    break;
                case "join":
                    addSystemMessage(data.user + " joined the chat.");
                    break;
                case "leave":
                    addSystemMessage(data.user + " left the chat.");
                    break;
            }
        };

        socket.onclose = function () {
            console.log("Disconnected from WebSocket server.");
            addSystemMessage("Connection closed.");
        };

        socket.onerror = function (error) {
            console.error("WebSocket error:", error);
        };
    }

    // UI helpers
    function addMessage(user, text) {
        $("#messages").append(
            `<div class="message"><span class="user">${user}:</span> <span class="text">${text}</span></div>`
        );
        $("#messages").scrollTop($("#messages")[0].scrollHeight);
    }

    function addSystemMessage(text) {
        $("#messages").append(
            `<div class="message"><em>${text}</em></div>`
        );
        $("#messages").scrollTop($("#messages")[0].scrollHeight);
    }

    function updateUserList(users) {
        $("#users").empty();
        users.forEach(function (user) {
            $("#users").append(`<li>${user}</li>`);
        });
    }
});
