<?php

namespace GPapakitsos\LaravelTraits;

use Illuminate\Database\Eloquent\Builder;

trait ModelActive
{
    /**
     * Returns the title of "active" attribute
     */
    private function getActiveField(): string
    {
        return config('laraveltraits.ModelActive.field') ?? 'active';
    }

    /**
     * Checks if model’s state is active
     */
    public function isActive(): bool
    {
        return (bool) $this->{$this->getActiveField()};
    }

    /**
     * Returns the title of model’s state
     */
    public function getActiveTitle(): string
    {
        return trans('laraveltraits::package.ModelActive.titles.'.$this->{$this->getActiveField()});
    }

    /**
     * Scope a query to only include active models
     */
    public function scopeActive(Builder $query): void
    {
        $query->where($this->getTable().'.'.$this->getActiveField(), true);
    }

    /**
     * Scope a query to only include inactive models
     */
    public function scopeNotActive(Builder $query): void
    {
        $query->where($this->getTable().'.'.$this->getActiveField(), false);
    }
}
