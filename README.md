# [Superfic Backend]

## Pre Setup

1. [Install PHP 7.2](https://www.php.net/downloads.php)

2. [Install Composer](https://getcomposer.org/download/)

3. Rename `.env.sample` to `.env`, and change the credentials in `.env` to your own.

## Local Development

### With Docker Compose

> Follow the instructions in the [README.md]() file.

### With PHP Built-in Server

1.  Change `MYSQL_DB_HOST=superfic-db` to `MYSQL_DB_HOST=127.0.0.1` in `.env` file

2.  Install packages

        composer install

3.  Start the server

        php -S localhost:8080

4.  Create the database with the `database/superfic_2022-04-01.sql` file in your MySQL setup.

5.  Populate the database

        curl -X GET "http://localhost:8080/index.php/ping"

## Endpoints

-   http://localhost:8080/index.php/get_all_posts
-   http://localhost:8080/index.php/get_posts?page=1&count=1
-   http://localhost:8080/index.php/get_posts_count?user_id=1
-   http://localhost:8080/index.php/get_posts_count?user_id=1&month=1
-   http://localhost:8080/index.php/get_monthly_posts_count?user_id=1
-   http://localhost:8080/index.php/get_average_characters_count?user_id=1
-   http://localhost:8080/index.php/get_longest_post?user_id=1
