<?php

namespace GPapakitsos\LaravelTraits;

use Carbon\Carbon;

trait TimestampsAccessor
{
    /**
     * created_at attribute accessor
     *
     * @param  mixed  $value
     * @return string|null
     */
    public function getCreatedAtAttribute($value)
    {
        return ! empty($value) ? Carbon::parse($value)->format(config('laraveltraits.TimestampsAccessor.format') ?? 'd/m/Y H:i:s') : null;
    }

    /**
     * updated_at attribute accessor
     *
     * @param  mixed  $value
     * @return string|null
     */
    public function getUpdatedAtAttribute($value)
    {
        return ! empty($value) ? Carbon::parse($value)->format(config('laraveltraits.TimestampsAccessor.format') ?? 'd/m/Y H:i:s') : null;
    }
}
