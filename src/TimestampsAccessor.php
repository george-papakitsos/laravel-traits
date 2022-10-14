<?php

namespace GPapakitsos\LaravelTraits;

use Carbon\Carbon;

trait TimestampsAccessor
{
    public function getCreatedAtAttribute($value)
    {
        return ! empty($value) ? Carbon::parse($value)->format(config('laraveltraits.TimestampsAccessor.format') ?? 'd/m/Y H:i:s') : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return ! empty($value) ? Carbon::parse($value)->format(config('laraveltraits.TimestampsAccessor.format') ?? 'd/m/Y H:i:s') : null;
    }
}
