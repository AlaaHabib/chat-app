const jwt = require('jsonwebtoken');
require('dotenv').config();
const secretKey = process.env.JWT_SECRET

const authenticateToken = (token, callback) => {

    if (!token) {
        callback('Unauthorized');
        return;
    }

    jwt.verify(token, secretKey, (err, decoded) => {
        if (err) {
            callback('Invalid token');
            return;
            }
        callback(null, decoded.userId);

    });
};

module.exports = authenticateToken;