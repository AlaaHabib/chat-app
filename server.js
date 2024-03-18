const express = require('express');
const authenticateToken = require('./authMiddleware');
const app = express();


const server = require('http').createServer(app);


const io = require('socket.io')(server, {
    cors: { origin: "*"}
});

io.on('connection', (socket) => {
    console.log('New client connected');

    // Handle user joining a private room with their user ID
    socket.on('joinRoom', (token) => {
        authenticateToken(token, (err, userId) => {
            if (err) {
                socket.emit('authenticationFailed');
                return;
            }
        socket.join(`private-chat-room-${userId}`);
        console.log(`User ${userId} joined room`);
        });
    });

    // Handle user leaving a room
    socket.on('disconnect', () => {
        console.log('Client disconnected');
    });
});

server.listen(3000, () => {
    console.log('Server is running');
});