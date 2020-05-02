<?php

namespace App\Models;

class ConstModel
{

    /**
     * Get all Ids
     */
    public static function getIds()
    {
        $ids = [];
        $className = static::class;

        foreach ((new $className)->getTypes() as $type) {
            $ids[] = $type['id'];
        }

        return $ids;
    }


    /**
     * Get
     *
     * @param mixed $id
     * @return \Illuminate\Support\Collection|mixed
     */
    public static function get( $id = null )
    {
        $className = static::class;
        if (empty($id)) {
            return collect((new $className)->getTypes());
        }

        return collect((new $className)->getTypes())->keyBy('id')->get($id);
    }
}
