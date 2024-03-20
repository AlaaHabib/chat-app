const express = require('express');
const authenticateToken = require('./authMiddleware');
const app = express();
require('dotenv').config();
const app_url = process.env.APP_URL

const server = require('http').createServer(app);
const axios = require('axios');


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
            const roomId = `private-chat-room-${userId}`;
            socket.join(roomId);
            
            socket.emit('roomJoined', roomId); 
            console.log(`User ${userId} joined room ${roomId}`);

        });
    });

    // Handle messages
    socket.on('chatMessage', async (data) => {
        authenticateToken(data.token, async (err, userId) => {
            if (err) {
                socket.emit('authenticationFailed');
                return;
            }
            // To Save Message crypted in database
                const message = {
                    receiver_id: data.receiverId,
                    message: data.message
                };
                axios.post(`${app_url}/api/v1/send-message`, message, {
                headers: {
                    'Authorization': `Bearer ${data.token}`
                 }
                })
                .then(response => {
                    console.log('Message saved successfully:', response.data);
                })
                .catch(error => {
                    console.error('Error saving message:', error);
                });
                io.to(`private-chat-room-${data.receiverId}`).emit('chatMessage', {
                    senderId: userId,
                    message: data.message
                });
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