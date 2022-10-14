<?php

namespace GPapakitsos\LaravelTraits;

trait ModelActive
{
    /**
     * Returns the title of "active" attribute
     *
     * @return string
     */
    private function getActiveField()
    {
        return config('laraveltraits.ModelActive.field') ?? 'active';
    }

    /**
     * Checks if model's state is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->{$this->getActiveField()};
    }

    /**
     * Returns the title of model's state
     *
     * @return string
     */
    public function getActiveTitle()
    {
        return trans('laraveltraits::package.ModelActive.titles.'.$this->{$this->getActiveField()});
    }

    /**
     * Scope a query to only include active models
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where($this->getTable().'.'.$this->getActiveField(), 1);
    }

    /**
     * Scope a query to only include inactive models
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeNotActive($query)
    {
        $query->where($this->getTable().'.'.$this->getActiveField(), 0);
    }
}
