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
    use HasFactory, ModelActive, ModelFile, TimestampsAccessor;

    const FILE_MODEL_ATTRIBUTE = 'avatar';
    const FILE_INPUT_FIELD = 'avatar_input';
    const FILE_FOLDER = 'avatars';
    const FILE_STORAGE_DISK = 'local';
    const FILE_DEFAULT_ASSET_URL = 'avatars/default.png';
    const FILE_USE_SUBFOLDER = true;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * Validation rules of the model
     *
     * @var array
     */
    public $validations = [
        'add' => [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:30',
        ],
        'edit' => [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|min:8|max:30',
        ],
    ];

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
