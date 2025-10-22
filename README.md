## Blog
Assigment Project

## About This Project
This project is a blog application built using the Laravel framework. It allows users to create, read, update, and delete blog posts. The application features user authentication.

## Requirements
- Git
- Docker

## Installation
1. Clone the repository:
   ```bash
   git clone git@github.com:kuridza/blog.git
    ```
2. Build and start the Docker containers:
   ```bash
   docker-compose up -d --build
   ```
3. Install dependencies:
   ```bash
   docker-compose exec app composer install
   ```
4. Generate the application key:
   ```bash
   docker-compose exec app php artisan key:generate
    ```
5. Run database migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```
6. Seed the database with sample data:
   ```bash
   docker-compose exec app php artisan db:seed
   ```
7. Run workers for queues:
   ```bash
   docker-compose exec -d app php artisan queue:work --daemon
   ```
8. (Optional) If you want to run tests, execute:
   ```bash
    docker-compose exec app php artisan test
    ```
9. Access the application at `http://localhost:8000`.

## Usage
- Log in to the application. With credentials:
  - Admin: admin@example.com : admin
  - Moderator: moderator@example.com : mod
  - User: user@example.com : user
- Create, edit, and delete blog posts.
- View a list of all blog posts.
- Use api endpoint for blog posts at `http://localhost:8000/api/posts`.
  - You can use query parameters to filter posts by user_role, risk_level, created_at, flagged, comments_count and content_length. To define relationship use eq, gt, gte, lt, lte as filter array key. To define mode use and/or as filter array key. For example:
    - `http://localhost:8000/api/posts?user_role[eq]=ADMIN`
    - `http://localhost:8000/api/posts?risk_level[eq]=high`
    - `http://localhost:8000/api/posts?comments_count[eq]=30&flagged[eq]=1&flagged[mode]=or`
  - There is also an endpoint with same filters to get statistics at `http://localhost:8000/api/stats`. 
- There is command to archive old posts, which can be added to cron job:
   ```bash
   docker-compose exec app php artisan archive:posts --days=30
   ```
