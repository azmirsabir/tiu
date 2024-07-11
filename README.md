# TIU

## Installation
PHP version: 7.3 - 8.0 
Follow these steps to set up the project locally:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/azmirsabir/tiu.git
    ```

2. **Install dependencies:**

    ```bash
    cd tiu
    composer install
    ```

3. **Copy the example environment file:**

    ```bash
    cp .env.example .env
    ```

4. **Generate application key:**

    ```bash
    php artisan key:generate
    ```

5. **Configure the database:**

   *Update the .env file with your database credentials:*

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_name
    DB_USERNAME=username
    DB_PASSWORD=password
    ```

7. **Run database migrations and seeders:**

    ```bash
    php artisan migrate --seed
    ```

8. **Start the development server:**

    ```bash
    php artisan serve
    ```

9. **Login with the default user (Product Owner) using the Login API:**

    ```dotenv
    USERNAME=azmir
    PASSWORD=password
    type=Admin
    ```
