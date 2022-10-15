<?php

namespace GPapakitsos\LaravelTraits;

use ErrorException;

trait CRUDController
{
    /**
     * Checks if controller’s property is set
     *
     * @return void
     *
     * @throws ErrorException
     */
    private function controllerPropertiesAreSet()
    {
        foreach (['request', 'model'] as $property) {
            if (! isset($this->$property)) {
                throw new ErrorException('Controller’s property $'.$property.' is not set in '.self::class);
            }
        }
    }

    /**
     * Returns model’s JSON response by provided id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws ErrorException|\Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getResource()
    {
        $this->controllerPropertiesAreSet();

        $resource = $this->model->findOrFail($this->request->id);

        return response()->json($resource);
    }

    /**
     * Creates a new model if the request is valid
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Database\Eloquent\Model
     *
     * @throws ErrorException|\Illuminate\Validation\ValidationException
     */
    public function doAdd()
    {
        $this->controllerPropertiesAreSet();

        if (isset($this->model->validations['all']) || $this->model->validations['add']) {
            $this->validate($this->request, $this->model->validations['all'] ?? $this->model->validations['add']);
        }

        $input = $this->request->only($this->model->getFillable());
        $model = $this->model->create($input);

        if (isset($this->returnModelsFromCRUD) && $this->returnModelsFromCRUD === true) {
            return $model;
        }

        return response()->json(['message' => trans('laraveltraits::package.CRUDController.messages.save_success'), 'type' => 'success']);
    }

    /**
     * Updates the model if the request is valid
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Database\Eloquent\Model
     *
     * @throws ErrorException|\Illuminate\Validation\ValidationException|\Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function doEdit()
    {
        $this->controllerPropertiesAreSet();

        if (isset($this->model->validations['all']) || $this->model->validations['edit']) {
            $this->validate($this->request, $this->model->validations['all'] ?? $this->model->validations['edit']);
        }

        $input = $this->request->only($this->model->getFillable());
        $model = $this->model->findOrFail($this->request->id);
        $model->update($input);

        if (isset($this->returnModelsFromCRUD) && $this->returnModelsFromCRUD === true) {
            return $model;
        }

        return response()->json(['message' => trans('laraveltraits::package.CRUDController.messages.save_success'), 'type' => 'success']);
    }

    /**
     * Deletes a model
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws ErrorException|\Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function doDelete()
    {
        $this->controllerPropertiesAreSet();

        $model = $this->model->findOrFail($this->request->id);
        $result = $model->delete();

        return response()->json($result == true
            ? ['message' => trans('laraveltraits::package.CRUDController.messages.delete_success'), 'type' => 'success']
            : ['message' => trans('laraveltraits::package.CRUDController.messages.delete_fail'), 'type' => 'error']
        );
    }
}
