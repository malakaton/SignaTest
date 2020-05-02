<?php

namespace App\Models;

class BasicModel
{

    /**
     * Get all Ids
     *
     * @param string $attribute
     *
     * @return array
     */
    public static function getValuesByAttribute(string $attribute = 'id'): array
    {
        $values = [];
        $className = static::class;

        foreach ((new $className)->getTypes() as $type) {
            $values[] = $type[$attribute];
        }

        return $values;
    }


    /**
     * Get
     *
     * @param mixed $attribute
     * @param mixed $value
     * @return \Illuminate\Support\Collection|mixed
     */
    public static function get( $attribute = 'id', $value = null)
    {
        $className = static::class;
        if (empty($value)) {
            return collect((new $className)->getTypes());
        }

        return collect((new $className)->getTypes())->keyBy($attribute)->get($value);
    }
}
