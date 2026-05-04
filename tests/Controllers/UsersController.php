<?php

namespace GPapakitsos\LaravelTraits\Tests\Controllers;

use GPapakitsos\LaravelTraits\CRUDController;
use GPapakitsos\LaravelTraits\Tests\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use CRUDController;

    public function __construct(protected Request $request, protected User $model) {}

    public function doAddReturnModel(): JsonResponse
    {
        $this->returnModelsFromCRUD = true;

        return response()->json($this->doAdd());
    }
}
