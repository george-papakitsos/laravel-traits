<?php

namespace GPapakitsos\LaravelTraits\Tests\Models;

use Database\Factories\UserFactory;
use GPapakitsos\LaravelTraits\TimestampsAccessor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, TimestampsAccessor;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Relationships
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function userLogins()
    {
        return $this->hasMany(UserLogin::class);
    }
}
