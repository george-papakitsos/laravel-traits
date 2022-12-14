<?php

namespace GPapakitsos\LaravelTraits\Tests\Models;

use Database\Factories\CountryFactory;
use GPapakitsos\LaravelTraits\ModelOrdering;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, ModelOrdering;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return CountryFactory::new();
    }

    /**
     * Relationships
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scopes
     */
    public function scopeOrderingFilterBy($query, $fieldsAndValues)
    {
        foreach ($fieldsAndValues as $field => $value) {
            $query->where($field, $value);
        }
    }
}
