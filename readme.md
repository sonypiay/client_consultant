## How to run the application

### Requirements
- PHP 7.2
- Composer (<a target="_blank" href="https://getcomposer.org/">Download</a>)
- MariaDB 10.4.6

### Step - step
1. Open a command prompt
2. Run ``` composer update ```
3. Make .env file or copy .env.example to .env and change this line
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=YOUR_DB_NAME
DB_USERNAME=YOUR_DB_USERNAME
DB_PASSWORD=YOUR_DB_PASSWORD
```
4. Run ``` php artisan key:generate ```
5. Run ``` php artisan serve ```
