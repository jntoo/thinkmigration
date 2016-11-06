<?php

namespace Illuminate\Support\Facades;

/**
 * @see \Laravel\Schema\Builder
 */
class Schema extends Facade
{
    /**
     * Get a schema builder instance for a connection.
     *
     * @param  string  $name
     * @return \Laravel\Schema\Builder
     */
    public static function connection($name)
    {
        return static::$app['db']->connection($name)->getSchemaBuilder();
    }

    /**
     * Get a schema builder instance for the default connection.
     *
     * @return \Laravel\Schema\Builder
     */
    protected static function getFacadeAccessor()
    {
        return static::$app['db']->connection()->getSchemaBuilder();
    }
}
