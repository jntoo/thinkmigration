<?php

namespace Illuminate\Support\Facades;

/**
 * @see \Laravel\DatabaseManager
 * @see \Laravel\Connection\Connection
 */
class DB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'db';
    }
}
