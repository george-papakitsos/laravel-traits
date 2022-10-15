<?php

namespace GPapakitsos\LaravelTraits\Tests\Controllers;

use GPapakitsos\LaravelTraits\CRUDController;
use GPapakitsos\LaravelTraits\Tests\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use CRUDController;

    protected $request;
    protected $model;

    public function __construct(Request $request, User $model)
    {
        $this->request = $request;
        $this->model = $model;
    }
}
