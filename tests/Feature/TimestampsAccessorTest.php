<?php

namespace GPapakitsos\LaravelTraits\Tests\Feature;

use GPapakitsos\LaravelTraits\Tests\FeatureTestCase;

class TimestampsAccessorTest extends FeatureTestCase
{
    public function test_created_at()
    {
        $this->assertEquals('23/04/1981 10:00:00', $this->user->created_at);
    }

    public function test_updated_at()
    {
        $this->assertEquals(null, $this->user->updated_at);
    }
}
