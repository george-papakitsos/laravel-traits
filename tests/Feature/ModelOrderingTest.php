<?php

namespace GPapakitsos\LaravelTraits\Tests\Feature;

use GPapakitsos\LaravelTraits\Tests\FeatureTestCase;
use GPapakitsos\LaravelTraits\Tests\Models\Country;

class ModelOrderingTest extends FeatureTestCase
{
    public function test_get_new_ordering()
    {
        $this->assertEquals(2, Country::getNewOrdering());
    }

    public function test_get_new_ordering_with_filter()
    {
        $this->assertEquals(2, Country::getNewOrdering(['planet' => 'Earth']));
    }

    public function test_reset_ordering()
    {
        $newCountry = Country::factory()->create([
            'ordering' => Country::getNewOrdering(),
        ]);
        Country::factory()->create([
            'ordering' => Country::getNewOrdering(),
        ]);
        $newCountry->delete();
        Country::resetOrdering();

        $this->assertEquals(2, Country::find(3)->ordering);
    }

    public function test_reset_ordering_with_filter()
    {
        $newCountry = Country::factory()->create([
            'planet' => 'Mars',
            'ordering' => Country::getNewOrdering(['planet' => 'Mars']),
        ]);
        Country::factory()->create([
            'planet' => 'Mars',
            'ordering' => Country::getNewOrdering(['planet' => 'Mars']),
        ]);
        $newCountry->delete();
        Country::resetOrdering(['planet' => 'Mars']);

        $this->assertEquals(1, Country::find(3)->ordering);
    }
}
