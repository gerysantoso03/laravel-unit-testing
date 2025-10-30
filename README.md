# Laravel Unit Testing Project

This is a Laravel project set up for practicing unit testing with Pest PHP. The project includes a basic CRUD application for managing products and categories.

## Prerequisites

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Git

## Installation Steps

1. **Clone the repository**
```bash
git clone git@github.com:gerysantoso03/laravel-unit-testing.git
cd laravel-unit-testing
```

2. **Install PHP dependencies**
```bash
composer install && composer dump-autoload
```

3. **Configure environment file**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
   - Create a new MySQL database
   - Update the `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

5. **Run database migrations and seeders**
```bash
php artisan migrate --seed
```

6. **Install Node.js dependencies and build assets**
```bash
npm install
npm run build
```

7. **Start the development server**
```bash
composer run dev
```

The application will be available at `http://localhost:8000`

## Setting Up Pest PHP for Testing

Pest PHP is already included in the project's dependencies. However, if you need to set it up in a fresh installation:

1 **Remove PHP unit and install PEST**
```bash
composer remove phpunit/phpunit
composer require pestphp/pest --dev --with-all-dependencies
```

2. **Initialize Pest**
```bash
./vendor/bin/pest --init
```

3. **Create test files**
```bash
# Create a feature test
php artisan make:test ProductTest

# Create a unit test
php artisan make:test Product --unit
```

4. **Run tests**
```bash
# Run all tests
php artisan test

# Run tests with coverage (requires Xdebug or PCOV)
php artisan test --coverage
```

## Project Structure

- `app/Models/` - Contains Product and Category models
- `app/Http/Controllers/` - Contains controllers for CRUD operations
- `database/migrations/` - Database structure
- `database/factories/` - Model factories for testing
- `database/seeders/` - Database seeders
- `resources/views/` - Blade template files
- `routes/web.php` - Web routes
- `tests/` - Test files (to be added during training)

## Additional Notes

- The project uses Laravel Vite for asset compilation
- Authentication is implemented using Laravel's built-in authentication
- Database seeding will create sample products and categories
- Test files will be added during the training session

## Support

If you encounter any issues during setup, please check:
1. PHP and MySQL versions compatibility
2. Database connection settings
3. Node.js and NPM installation
4. File permissions in the storage and bootstrap/cache directories

## License

This project is open-sourced software licensed under the MIT license.
