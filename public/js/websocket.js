class TalkBoxWebSocket {
    constructor(url, username) {
        this.url = url;
        this.username = username;
        this.socket = null;
        this.onMessageCallback = null;
        this.onUserListCallback = null;
        this.onSystemCallback = null;
    }

    connect() {
        this.socket = new WebSocket(this.url);

        this.socket.onopen = () => {
            console.log("Connected to TalkBox WebSocket server.");
            // Notify server of new user
            this.send({
                type: "join",
                user: this.username
            });
        };

        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);

            switch (data.type) {
                case "message":
                    if (this.onMessageCallback) {
                        this.onMessageCallback(data.user, data.text);
                    }
                    break;
                case "userlist":
                    if (this.onUserListCallback) {
                        this.onUserListCallback(data.users);
                    }
                    break;
                case "join":
                case "leave":
                    if (this.onSystemCallback) {
                        this.onSystemCallback(`${data.user} ${data.type}ed the chat.`);
                    }
                    break;
                default:
                    console.warn("Unknown message type:", data);
            }
        };

        this.socket.onclose = () => {
            console.log("Disconnected from WebSocket server.");
            if (this.onSystemCallback) {
                this.onSystemCallback("Connection closed.");
            }
        };

        this.socket.onerror = (error) => {
            console.error("WebSocket error:", error);
        };
    }

    send(payload) {
        if (this.socket && this.socket.readyState === WebSocket.OPEN) {
            this.socket.send(JSON.stringify(payload));
        }
    }

    sendMessage(text) {
        this.send({
            type: "message",
            user: this.username,
            text: text
        });
    }

    // Register callbacks
    onMessage(callback) {
        this.onMessageCallback = callback;
    }

    onUserList(callback) {
        this.onUserListCallback = callback;
    }

    onSystem(callback) {
        this.onSystemCallback = callback;
    }
}
