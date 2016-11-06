<?php

namespace Laravel\Connection;

use Laravel\query\processors\SQLiteProcessor;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as DoctrineDriver;
use Laravel\query\grammars\SQLiteGrammar as QueryGrammar;
use Laravel\schema\grammars\SQLiteGrammar as SchemaGrammar;

class SQLiteConnection extends Connection
{
    /**
     * Get the default query grammar instance.
     *
     * @return \Laravel\query\grammars\SQLiteGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Laravel\schema\grammars\SQLiteGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Laravel\query\processors\SQLiteProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new SQLiteProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOSqlite\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}
