<?php

namespace Laravel\Connection;

use Laravel\schema\MySqlBuilder;
use Laravel\query\processors\MySqlProcessor;

use Doctrine\DBAL\Driver\PDOMySql\Driver as DoctrineDriver;

use Laravel\query\grammars\MySqlGrammar as QueryGrammar;
use Laravel\schema\grammars\MySqlGrammar as SchemaGrammar;

class MySqlConnection extends Connection
{
    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Laravel\schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new MySqlBuilder($this);
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Laravel\query\grammars\MySqlGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Laravel\schema\grammars\MySqlGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Laravel\query\processors\MySqlProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new MySqlProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOMySql\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}
