# Potter API Integration

An experimental Laravel application using Potter API.

## Requirements

- PHP 8.1 or higher
- Laravel 10.x
- Composer
- MySQL/MariaDB

## Installation

Clone Repo

```bash
  git clone https://github.com/xbringitback/spellbound_data
```

Install Composer

```bash
composer install
```

Set up environment

```bash
cp .env.example .env
php artisan key:generate
```

Configure database in .env

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Commands

```bash
php artisan import:all
php artisan characters:import
php artisan houses:import
php artisan spells:import

php artisan test tests/Unit/Services/SpellServiceTest.php


```
