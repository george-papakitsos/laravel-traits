# Laravel Model & Controller Traits
A bundle of some useful Laravel Model & Controller traits.

## Requirements
- [PHP >= 7.4](https://www.php.net/)
- [Laravel >= 7.0](https://laravel.com/)

## Installation
You can install the package via composer:
```bash
composer require gpapakitsos/laravel-traits
```

### TimestampsAccessor Trait
Transforms model's `created_at` & `updated_at` attributes into a format you can configure via the config file.

### ModelActive Trait
#### Available methods:
- isActive()
- getActiveTitle()

#### Available scopes:
- active()
- notActive()
