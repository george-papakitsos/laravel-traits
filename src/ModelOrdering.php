<?php

namespace GPapakitsos\LaravelTraits;

use ErrorException;

trait ModelOrdering
{
    /**
     * Returns the title of "ordering" attribute
     */
    private static function getOrderingField(): string
    {
        return config('laraveltraits.ModelOrdering.field') ?? 'ordering';
    }

    /**
     * Returns next available ordering value
     *
     * @throws ErrorException
     */
    public static function getNewOrdering(array $fieldsAndValues = []): int
    {
        if (! empty($fieldsAndValues) && ! method_exists(self::class, 'scopeOrderingFilterBy')) {
            throw new ErrorException('Method scopeOrderingFilterBy is not set in '.self::class);
        }

        $field = self::getOrderingField();

        return empty($fieldsAndValues)
            ? self::max($field) + 1
            : self::orderingFilterBy($fieldsAndValues)->max($field) + 1;
    }

    /**
     * Resets ordering
     *
     * @throws ErrorException
     */
    public static function resetOrdering(array $fieldsAndValues = []): void
    {
        if (! empty($fieldsAndValues) && ! method_exists(self::class, 'scopeOrderingFilterBy')) {
            throw new ErrorException('Method scopeOrderingFilterBy is not set in '.self::class);
        }

        $field = self::getOrderingField();

        $items = self::orderBy($field);
        if (! empty($fieldsAndValues)) {
            $items->orderingFilterBy($fieldsAndValues);
        }
        $items = $items->get();

        $ordering = 0;
        foreach ($items as $item) {
            $item->$field = ++$ordering;
            $item->save();
        }
    }
}
