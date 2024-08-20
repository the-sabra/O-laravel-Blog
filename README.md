# O laravel Blog

This project is a web application built using the Laravel Filament admin panel framework. The dashboard is a versatile platform enabling users to register, log in, add posts, and engage in discussions through comments. Additionally, it features an administrative account for content moderation

## Project Overview

A simple API that allows users to register, log in, add posts, view posts, and comment on them.

The project also includes an admin account built with `filamentphp` to manage and delete inappropriate posts.

## Features

-   User Authentication: Register and login functionality with `JWT`.
-   Post Management: Users can add posts, and others can view and comment on them.
-   Admin Dashboard: An admin account for managing and deleting posts.

## Technologies Used

-   [FilamentPhp](https://filamentphp.com/): The admin panel framework used for building the dashboard.

-   [Laravel](https://laravel.com/): The backend framework that seamlessly integrates with FilamentPhp

-   [PostgreSQL](https://www.postgresql.org/): The Relational Database Management System (RDBMS) choosen to store the data 

## Installation

Follow these steps to set up and run the project locally:

1. Clone the repository:

    ```bash
    git clone https://github.com/the-sabra/Morph-Blog.git
    ```

2. Navigate to the project directory:
    ```bash
    cd Morph Blog
    ```
3. Install dependencies:
    ```bash
    composer install
    ```
4. Set up the database:
    ```bash
    php artisan migrate
    ```
5. Start the development server:
    ````bash
    php artisan serve
    ````

## Simple Example of API routes 

`/auth/register`: this for register new user(author)

`/auth/login`: this for login and gives you `bearer token` to access to the system functionalities

### For more info about API routes [API Documentation](https://documenter.getpostman.com/view/22968167/2s9Ykq71Rq)
