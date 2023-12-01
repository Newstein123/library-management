# Laravel App with Docker and Sail

This is a Laravel application with Docker support using Laravel Sail.

## Requirements

- [Docker](https://www.docker.com/)
- [Laravel Sail](https://laravel.com/docs/8.x/sail#introduction)

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Newstein123/library-management.git

2. Change into your project directory

    ```bash
    cd library-management

3. Copy the .env.example file     

    ```
    cp .env.example .env

4. Generate the application key

    ```
    sail artisan key:generate or php artisan key:generate 

5. Run the migration and seed the database

    ```
    sail artisan migrate --seed or php artisan migrate:fresh --seed

6. Start the Laravel Sail development environment:

    ```
    sail up

## Admin Login

- username - admin@gmail.com
- password - password 

## Editor Login

- username - editor@gmail.com
- password - password 

## User Login

- username - user@gmail.com
- password - password 
