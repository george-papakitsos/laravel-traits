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

The service provider will automatically get registered. Optionally, you may manually add the service provider in your `config/app.php` file:
```php
'providers' => [
    // ...
    GPapakitsos\LaravelTraits\TraitsServiceProvider::class,
];
```

You should publish the config file with the following command:
```shell
php artisan vendor:publish --provider="GPapakitsos\LaravelTraits\TraitsServiceProvider"
```

---
### TimestampsAccessor Trait
Transforms model’s `created_at` & `updated_at` attributes into a format you can configure in your `config/laraveltraits.php` file.

To enable this feature on a model, you must use the `GPapakitsos\LaravelTraits\TimestampsAccessor` trait.

---
### ModelActive Trait
Some useful methods and scopes to handle model’s `active` or `inactive` state. You can configure the model’s "active" attribute in your `config/laraveltraits.php` file.

To enable this feature on a model, you must use the `GPapakitsos\LaravelTraits\ModelActive` trait.
#### Available methods:
```php
$model->isActive()
```
```php
$model->getActiveTitle()
```

#### Available scopes:
```php
$model->active()
```
```php
$model->notActive()
```

---
### ModelOrdering Trait
Some useful methods to handle model’s `ordering` feature. You can configure the model’s "ordering" attribute in your `config/laraveltraits.php` file.

To enable this feature on a model, you must use the `GPapakitsos\LaravelTraits\ModelOrdering` trait.
#### Available methods:
```php
$model::getNewOrdering()
```
```php
$model::getNewOrdering(['category' => 1])
```
```php
$model::resetOrdering()
```
```php
$model::resetOrdering(['category' => 1])
```
