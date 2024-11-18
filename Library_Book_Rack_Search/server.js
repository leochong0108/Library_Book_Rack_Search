import express from 'express';
import http from 'http';
import { Server } from 'socket.io';

const app = express();
const server = http.createServer(app);

// Set up Socket.io with CORS configuration
const io = new Server(server, {
    cors: {
        origin: "http://127.0.0.1:8000",  // Allow your frontend URL
        methods: ["GET", "POST"],
        allowedHeaders: ["*"],  // Optionally allow all headers
    },
});

app.use(express.json()); // Middleware to parse JSON body

app.post('/rent-book', (req, res) => {
    const { book_id, is_available } = req.body;

    // Emit an event to all connected clients
    io.emit('bookRented', { book_id, is_available });

    res.status(200).json({ success: true });
});

app.post('/return-book', (req, res) => {
    const { book_id, is_available } = req.body;

    // Emit an event to all connected clients
    io.emit('bookReturned', { book_id, is_available });

    res.status(200).json({ success: true });
});

io.on('connection', (socket) => {
    console.log('A user connected');
    socket.on('disconnect', () => {
        console.log('User disconnected');
    });
});

server.listen(3000, () => {
    console.log('Server listening on port 3000');
});
