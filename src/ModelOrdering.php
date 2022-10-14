<?php

namespace GPapakitsos\LaravelTraits;

trait ModelOrdering
{
    /**
     * Returns the title of "ordering" attribute
     *
     * @return string
     */
    private static function getOrderingField()
    {
        return config('laraveltraits.ModelOrdering.field') ?? 'ordering';
    }

    /**
     * Returns next available ordering value
     *
     * @param  array  $fieldsAndValues
     * @return int
     */
    public static function getNewOrdering($fieldsAndValues = [])
    {
        if (! empty($fieldsAndValues) && ! method_exists(self::class, 'scopeOrderingFilterBy')) {
            throw new \ErrorException('Method scopeOrderingFilterBy is not set in '.self::class);
        }

        $field = self::getOrderingField();

        return empty($fieldsAndValues)
            ? self::max($field) + 1
            : self::orderingFilterBy($fieldsAndValues)->max($field) + 1;
    }

    /**
     * Resets ordering
     *
     * @param  array  $fieldsAndValues
     * @return void
     */
    public static function resetOrdering($fieldsAndValues = [])
    {
        if (! empty($fieldsAndValues) && ! method_exists(self::class, 'scopeOrderingFilterBy')) {
            throw new \ErrorException('Method scopeOrderingFilterBy is not set in '.self::class);
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
