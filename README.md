## Chat app

## System Requirements
php 8.1 or above mysql

## Installation steps
Run the following commands

- composer install / update
- npm install
- copy .env.example as .env file and set database credentials
- php artisan migrate --seed
- php artisan key:generate
- php artisan l5-swagger:generate
- php artisan serve 
- node server.js

## Using 

- Repository pattern for saving messages 
- Socket io , Socket io client to send message in private room 
- Open SSL with AES algorithm to encrypt messages 
- Global Handler 
- Translation for All request by add header locale (en , ar)
- Swagger Documentation

## Swagger Doc.
View http://localhost:8000/api/documentation
