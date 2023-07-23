# Getting started

## Installation

Clone the repository

    git clone https://github.com/mohit-gh/TicketSystem.git

Switch to the repo folder

    cd TicketSystem

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Update Following in .env

    DB details
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=mysql
        DB_DATABASE=
        DB_USERNAME=
        DB_PASSWORD=
    
Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000
