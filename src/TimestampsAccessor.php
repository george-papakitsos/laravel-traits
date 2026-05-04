<?php

namespace GPapakitsos\LaravelTraits;

use Carbon\Carbon;

trait TimestampsAccessor
{
    /**
     * created_at attribute accessor
     */
    public function getCreatedAtAttribute(mixed $value): ?string
    {
        return $this->timestampsFormatter($value);
    }

    /**
     * updated_at attribute accessor
     */
    public function getUpdatedAtAttribute(mixed $value): ?string
    {
        return $this->timestampsFormatter($value);
    }

    /**
     * Transforms the provided timestamp to a formatted string
     */
    private function timestampsFormatter(?string $value): ?string
    {
        return ! empty($value) ? Carbon::parse($value)->setTimezone(config('app.timezone'))->format(config('laraveltraits.TimestampsAccessor.format') ?? 'd/m/Y H:i:s') : null;
    }
}
