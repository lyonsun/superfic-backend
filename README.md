# Superfic Backend

> _Live Preview of this prroject on Heroku can be found [here](https://superfic-backend.herokuapp.com/)._

## Pre Setup

1.  [Install PHP 7.2](https://www.php.net/downloads.php)

2.  [Install Composer](https://getcomposer.org/download/)

3.  Clone the repository:

        git clone git@github.com:lyonsun/superfic-backend.git

4.  Rename `.env.sample` to `.env`, and change the credentials in `.env` to your own.

## Local Development

### With Docker Compose

> Follow the instructions in this [README.md](https://github.com/lyonsun/superfic-social-network/blob/main/README.md) file.

### With PHP Built-in Server

1.  Change `MYSQL_DB_HOST=superfic-db` to `MYSQL_DB_HOST=127.0.0.1` in `.env` file

2.  Install packages

        composer install

3.  Start the server

        php -S localhost:8080

4.  Create the database with the `database/superfic_2022-04-01.sql` file in your MySQL setup.

5.  Populate the database

        curl -X GET "http://localhost:8080/index.php/ping"

### Endpoints

-   http://localhost:8080/index.php/get_all_posts
-   http://localhost:8080/index.php/get_posts?page=1&count=1
-   http://localhost:8080/index.php/get_posts_count?user_id=1
-   http://localhost:8080/index.php/get_posts_count?user_id=1&month=1
-   http://localhost:8080/index.php/get_monthly_posts_count?user_id=1
-   http://localhost:8080/index.php/get_average_characters_count?user_id=1
-   http://localhost:8080/index.php/get_longest_post?user_id=1

## Deployment

### To Heroku

1.  Fork this repository to your own repository.

2.  Create a new Heroku app either from the terminal or heroku.com site.

3.  Add the JawsDB MySQL addon to your Heroku app, free plan will be enough.

4.  Login to the database created JawsDB MySQL addon, via Sequel Pro, TablePlus, or other MySQL client tools.

5.  Dump the database with the `database/02-dump-tables-superfic_2022-04-01.sql` file.

6.  Populate the database:

    -   if you are using a paid plan, you can just run the following command:

            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping"

    -   if you are using a free plan, you need to run all the following commands one after another:

            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/users"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/1"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/2"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/3"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/4"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/5"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/6"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/7"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/8"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/9"
            curl -X GET "https://superfic-backend.herokuapp.com/index.php/ping/posts/10"

7.  Go to your Heroku app -> Settings -> Config Vars, click `Reveal Config Vars`

8.  Add config vars with the key and value between line #6 to line #9 in the `.env` file. Or make your own value for those four keys.

9.  Go to your Heroku app -> Deploy -> Deployment method, select `GitHub` to connect your GitHub repository.

10. Make any changes to the code, commit and push to your GitHub repository. The commitment will be automatically deployed to Heroku.

11. Visit your Heroku app in the browser, you can then access all the endpoints listed above by replacing `http://localhost:8080` with the root URL of your Heroku app.

### Live deployment on Heroku

You can find my Heroku deployment of this project [here](https://superfic-backend.herokuapp.com/).
