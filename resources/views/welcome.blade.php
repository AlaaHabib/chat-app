<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Socket.IO Test</title>
</head>
<body>
    <h1>Socket.IO Test</h1>
    <button onclick="joinRoom()">Join Room</button>
    <button onclick="sendMessage()">Send Message</button>

    <div id="messages"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.2.0/socket.io.js"></script>
    <script>
        const socket = io('http://localhost:3000'); 

        socket.on('connect', () => {
            console.log('Connected to Socket.IO server');
        });

        socket.on('authenticationFailed', () => {
            console.error('Authentication failed');
        });

        socket.on('roomJoined', (roomId) => {
            console.log('Joined room:', roomId);
            // Listen to messages in the received room ID
            socket.on(roomId, (data) => {
                console.log('Received message:', data);
                // Display the received message
                const messagesDiv = document.getElementById('messages');
                messagesDiv.innerHTML += `<p>Message from user ${data.senderId}: ${data.message}</p>`;
            });
        });

        function joinRoom() {
            const token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDEvYXBpL3YxL2xvZ2luIiwiaWF0IjoxNzEwOTQyMDk0LCJleHAiOjE3MTA5NDU2OTQsIm5iZiI6MTcxMDk0MjA5NCwianRpIjoiQXNrSTZ5ZWRuU25FY0FNYyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcklkIjoxfQ.AkyCTE1OzItHazkUjWco63P3fKo3oImt4O0DsFV1WcA'; // Replace with a valid authentication token
            socket.emit('joinRoom', token);
        }

        function sendMessage() {
            const messageData = {
                token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDEvYXBpL3YxL2xvZ2luIiwiaWF0IjoxNzEwOTQyMDk0LCJleHAiOjE3MTA5NDU2OTQsIm5iZiI6MTcxMDk0MjA5NCwianRpIjoiQXNrSTZ5ZWRuU25FY0FNYyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcklkIjoxfQ.AkyCTE1OzItHazkUjWco63P3fKo3oImt4O0DsFV1WcA', // Replace with a valid authentication token
                receiverId: 1, // Replace with the recipient's ID
                message: 'Test message'
            };
            socket.emit('chatMessage', messageData);
        }
    </script>
</body>
</html>
