<?php

namespace GPapakitsos\LaravelTraits\Tests\Feature;

use GPapakitsos\LaravelTraits\Tests\FeatureTestCase;

class CRUDControllerTest extends FeatureTestCase
{
    public function test_get_resource_model_not_found()
    {
        $response = $this->get('users/get-resource/777');
        $response->assertStatus(404);
    }

    public function test_get_resource_model_found()
    {
        $response = $this->get('users/get-resource/'.$this->user->id);
        $response->assertStatus(200);
        $this->assertEquals($this->user->id, $response->getData(true)['id']);
    }

    public function test_do_add_validation_failed()
    {
        $response = $this->post('users/add', [
            'name' => 'George Papakitsos',
            'email' => 'george@papakitsos.gr',
        ]);
        $response->assertStatus(302);
    }

    public function test_do_add_success()
    {
        $response = $this->post('users/add', [
            'name' => 'George Papakitsos',
            'email' => 'george@papakitsos.gr',
            'password' => '12345678',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'type'], $response->getData(true));
    }

    public function test_do_edit_validation_failed()
    {
        $response = $this->post('users/edit', [
            'id' => $this->user->id,
            'name' => 'George Papakitsos',
        ]);
        $response->assertStatus(302);
    }

    public function test_do_edit_model_not_found()
    {
        $response = $this->post('users/edit', [
            'id' => 777,
            'name' => 'George Papakitsos',
            'email' => 'george@papakitsos.gr',
        ]);
        $response->assertStatus(404);
    }

    public function test_do_edit_success()
    {
        $response = $this->post('users/edit', [
            'id' => $this->user->id,
            'name' => 'George Papakitsos',
            'email' => 'george@papakitsos.gr',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'type'], $response->getData(true));
    }

    public function test_do_delete_model_not_found()
    {
        $response = $this->post('users/delete', [
            'id' => 777,
        ]);
        $response->assertStatus(404);
    }

    public function test_do_delete_success()
    {
        $response = $this->post('users/delete', [
            'id' => $this->user->id,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'type'], $response->getData(true));
    }
}
