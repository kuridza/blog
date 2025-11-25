## Advertisements
Assigment Project

## About This Project
This project is a advertisements application built using the Laravel framework.

## Requirements
- Git
- Docker

## Installation
1. Build and start the Docker containers:
   ```bash
   docker-compose up -d --build
   ```
2. Install dependencies:
   ```bash
   docker-compose exec app composer run-script setup
   ```
3. Seed the database with sample data:
   ```bash
   docker-compose exec app php artisan db:seed
   ```
4. Access the application at `http://localhost:8000`.

## Usage
- Log in to the application. With credentials:
  - Admin: admin@example.com : admin
  - User: user@example.com : user

