<?php

namespace GPapakitsos\LaravelTraits\Tests\Feature;

use GPapakitsos\LaravelTraits\Tests\FeatureTestCase;
use GPapakitsos\LaravelTraits\Tests\Models\User;

class ModelActiveTest extends FeatureTestCase
{
    public function test_is_active()
    {
        $this->assertTrue($this->user->isActive());
    }

    public function test_get_active_title()
    {
        $this->assertEquals(trans('laraveltraits::package.ModelActive.titles.1'), $this->user->getActiveTitle());
    }

    public function test_scope_active()
    {
        $this->assertTrue(User::active()->first()->isActive());
    }

    public function test_scope_not_active()
    {
        $this->assertFalse(User::notActive()->first()->isActive());
    }
}
