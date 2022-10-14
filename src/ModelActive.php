<?php

namespace GPapakitsos\LaravelTraits;

trait ModelActive
{
    private function getActiveField()
    {
        return config('laraveltraits.ModelActive.field') ?? 'active';
    }

    public function isActive()
    {
        return (bool) $this->{$this->getActiveField()};
    }

    public function getActiveTitle()
    {
        return trans('laraveltraits::package.ModelActive.titles.'.$this->{$this->getActiveField()});
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable().'.'.$this->getActiveField(), 1);
    }

    public function scopeNotActive($query)
    {
        return $query->where($this->getTable().'.'.$this->getActiveField(), 0);
    }
}
