<?php

namespace GPapakitsos\LaravelTraits\Tests\Models;

use Database\Factories\UserFactory;
use GPapakitsos\LaravelTraits\ModelActive;
use GPapakitsos\LaravelTraits\ModelFile;
use GPapakitsos\LaravelTraits\TimestampsAccessor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, TimestampsAccessor, ModelActive, ModelFile;

    const FILE_MODEL_ATTRIBUTE = 'avatar';
    const FILE_INPUT_FIELD = 'avatar_input';
    const FILE_FOLDER = 'avatars';
    const FILE_STORAGE_DISK = 'local';
    const FILE_DEFAULT_ASSET_URL = 'avatars/default.png';

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
}
