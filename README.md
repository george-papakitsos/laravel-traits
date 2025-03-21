# Laravel Model & Controller Traits
A bundle of some useful Laravel Model & Controller traits.

## Requirements
- [PHP >= 7.4](https://www.php.net/)
- [Laravel >= 8.0](https://laravel.com/)

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
Transforms model’s `created_at` and `updated_at` attributes into a format you can configure in your `config/laraveltraits.php` file.

To enable this feature on a model, you must use the `GPapakitsos\LaravelTraits\TimestampsAccessor` trait.

---
### ModelActive Trait
Some useful methods and scopes to handle model’s `active` or `inactive` state. You can configure the model’s "active" attribute in your `config/laraveltraits.php` file.

To enable this feature on a model, you must use the `GPapakitsos\LaravelTraits\ModelActive` trait.
#### Available methods:
```php
/**
 * Checks if model’s state is active
 *
 * @return bool
 */
$model->isActive();
```
```php
/**
 * Returns the title of model’s state
 *
 * @return string
 */
$model->getActiveTitle();
```

#### Available scopes:
```php
/**
 * Scope a query to only include active models
 *
 * @param  \Illuminate\Database\Eloquent\Builder  $query
 * @return void
 */
$model->active()->get();
```
```php
/**
 * Scope a query to only include inactive models
 *
 * @param  \Illuminate\Database\Eloquent\Builder  $query
 * @return void
 */
$model->notActive()->get();
```

---
### ModelOrdering Trait
Some useful methods to handle model’s `ordering` feature. You can configure the model’s "ordering" attribute in your `config/laraveltraits.php` file.

To enable this feature on a model, you must use the `GPapakitsos\LaravelTraits\ModelOrdering` trait.
#### Available methods:
```php
/**
 * Returns next available ordering value
 *
 * @param  array  $fieldsAndValues
 * @return int
 *
 * @throws ErrorException
 */
$model::getNewOrdering();
// or
$model::getNewOrdering(['category' => 1]);
```
```php
/**
 * Resets ordering
 *
 * @param  array  $fieldsAndValues
 * @return void
 *
 * @throws ErrorException
 */
$model::resetOrdering();
// or
$model::resetOrdering(['category' => 1]);
```

---
### ModelFile Trait
Some useful methods to handle file upload on a model; for example user’s avatar.

To enable this feature on a model, you must use the `GPapakitsos\LaravelTraits\ModelFile` trait and you have to define the following constants:
```php
<?php

namespace App\Models;

use GPapakitsos\LaravelTraits\ModelFile;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use ModelFile;

    const FILE_MODEL_ATTRIBUTE = 'avatar'; // The model’s attribute name
    const FILE_INPUT_FIELD = 'avatar_input'; // The form’s input field name
    const FILE_FOLDER = 'avatars'; // The folder name of the storage disk
    const FILE_STORAGE_DISK = 'local'; // The storage disk
    const FILE_DEFAULT_ASSET_URL = 'avatars/default.png'; // The default asset if file does not exist
    const FILE_USE_SUBFOLDER = true; // [Optional] If true, the file will be stored in a random subdirectory into FILE_FOLDER path
}
```

#### Available methods:
```php
/**
 * Stores file if exists & adds the path of the uploaded file into request object
 *
 * @param  \Illuminate\Http\Request  $request
 * @return void
 *
 * @throws ErrorException|\Illuminate\Validation\ValidationException
 */
$user::storeFile($request);
```
```php
/**
 * Deletes model’s file if exists
 *
 * @return void
 */
$user->deleteFile();
```
```php
/**
 * Removes the previous file if exists & stores the new one
 *
 * @param  \Illuminate\Http\Request  $request
 * @return void
 *
 * @throws ErrorException|\Illuminate\Validation\ValidationException
 */
$user::changeFile($request);
```
```php
/**
 * Returns file’s URL
 *
 * @return string|null
 */
$user->getFileURL();
```
```php
/**
 * Returns file’s path
 *
 * @return string|null
 */
$user->getFilePath();
```

---
### CRUDController Trait
A useful trait to handle model’s CRUD function inside a controller.

To enable this feature on a controller, you must use the `GPapakitsos\LaravelTraits\CRUDController` trait and you have to define the `request` and `model` properties at your controller:
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use GPapakitsos\LaravelTraits\CRUDController;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use CRUDController;

    protected $request;
    protected $model;

    // You can set the property $returnModelsFromCRUD as `true` if you would like the methods `doAdd` & `doEdit` to return the model
    // protected $returnModelsFromCRUD = true;

    public function __construct(Request $request, User $model)
    {
        $this->request = $request;
        $this->model = $model;
    }
}
```

After that, you have to define the routes of the controller in your `routes/web.php` file:
```php
Route::prefix('users')->group(function () {
    Route::get('get-resource/{id?}', 'UsersController@getResource');
    Route::post('add', 'UsersController@doAdd');
    Route::post('edit', 'UsersController@doEdit');
    Route::post('delete', 'UsersController@doDelete');
});
```

Finally, you have to define the `fillable` property and the validation rules of your model:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * Validation rules of the model
     *
     * @var array
     */
    public $validations = [
        'add' => [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:30',
        ],
        'edit' => [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|min:8|max:30',
        ],
        // or you can set only the array with key `all`, if the rules are the same between `add` & `edit`
        // 'all' => [
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|max:255',
        //     'password' => 'required|min:8|max:30',
        // ],
    ];
}
```

#### Available controller methods:
```php
/**
 * Returns model’s JSON response by provided id
 *
 * @return \Illuminate\Http\JsonResponse
 *
 * @throws ErrorException|\Illuminate\Database\Eloquent\ModelNotFoundException
 */
getResource();
```

```php
/**
 * Creates a new model if the request is valid
 *
 * @return \Illuminate\Http\JsonResponse|\Illuminate\Database\Eloquent\Model
 *
 * @throws ErrorException|\Illuminate\Validation\ValidationException
 */
doAdd();
```

```php
/**
 * Updates the model if the request is valid
 *
 * @return \Illuminate\Http\JsonResponse|\Illuminate\Database\Eloquent\Model
 *
 * @throws ErrorException|\Illuminate\Validation\ValidationException|\Illuminate\Database\Eloquent\ModelNotFoundException
 */
doEdit();
```

```php
/**
 * Deletes a model
 *
 * @return \Illuminate\Http\JsonResponse
 *
 * @throws ErrorException|\Illuminate\Database\Eloquent\ModelNotFoundException
 */
doDelete();
```
