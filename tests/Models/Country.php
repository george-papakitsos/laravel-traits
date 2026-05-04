<?php

namespace GPapakitsos\LaravelTraits\Tests\Models;

use Database\Factories\CountryFactory;
use GPapakitsos\LaravelTraits\ModelOrdering;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[WithoutTimestamps]
class Country extends Model
{
    use HasFactory, ModelOrdering;

    /**
     * Create a new factory instance for the model.
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
    public function scopeOrderingFilterBy(Builder $query, array $fieldsAndValues)
    {
        foreach ($fieldsAndValues as $field => $value) {
            $query->where($field, $value);
        }
    }
}
