<?php

namespace Laravel\Connection;

use Laravel\schema\PostgresBuilder;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as DoctrineDriver;
use Laravel\query\processors\PostgresProcessor;
use Laravel\query\grammars\PostgresGrammar as QueryGrammar;
use Laravel\schema\grammars\PostgresGrammar as SchemaGrammar;

class PostgresConnection extends Connection
{
    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Laravel\schema\PostgresBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new PostgresBuilder($this);
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Laravel\query\grammars\PostgresGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Laravel\schema\grammars\PostgresGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Laravel\query\processors\PostgresProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new PostgresProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOPgSql\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}
