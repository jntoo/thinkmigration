<?php

namespace Laravel\Connection;

use Exception;
use Laravel\Support\Str;

trait DetectsDeadlocks
{
    /**
     * Determine if the given exception was caused by a deadlock.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function causedByDeadlock(Exception $e)
    {
        $message = $e->getMessage();

        return Str::contains($message, [
            'Deadlock found when trying to get lock',
            'deadlock detected',
            'The database file is locked',
            'A table in the database is locked',
            'has been chosen as the deadlock victim',
        ]);
    }
}
