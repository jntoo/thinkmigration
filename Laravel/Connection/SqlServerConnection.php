<?php

namespace Laravel\Connection;

use Closure;
use Exception;
use Throwable;
use Doctrine\DBAL\Driver\PDOSqlsrv\Driver as DoctrineDriver;
use Laravel\query\processors\SqlServerProcessor;
use Laravel\query\grammars\SqlServerGrammar as QueryGrammar;
use Laravel\schema\grammars\SqlServerGrammar as SchemaGrammar;

class SqlServerConnection extends Connection
{
    /**
     * Execute a Closure within a transaction.
     *
     * @param  \Closure  $callback
     * @param  int  $attempts
     * @return mixed
     *
     * @throws \Exception|\Throwable
     */
    public function transaction(Closure $callback, $attempts = 1)
    {
        for ($a = 1; $a <= $attempts; $a++) {
            if ($this->getDriverName() == 'sqlsrv') {
                return parent::transaction($callback);
            }

            $this->getPdo()->exec('BEGIN TRAN');

            // We'll simply execute the given callback within a try / catch block
            // and if we catch any exception we can rollback the transaction
            // so that none of the changes are persisted to the database.
            try {
                $result = $callback($this);

                $this->getPdo()->exec('COMMIT TRAN');
            }

            // If we catch an exception, we will roll back so nothing gets messed
            // up in the database. Then we'll re-throw the exception so it can
            // be handled how the developer sees fit for their applications.
            catch (Exception $e) {
                $this->getPdo()->exec('ROLLBACK TRAN');

                throw $e;
            } catch (Throwable $e) {
                $this->getPdo()->exec('ROLLBACK TRAN');

                throw $e;
            }

            return $result;
        }
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Laravel\query\grammars\SqlServerGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Laravel\schema\grammars\SqlServerGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Laravel\query\processors\SqlServerProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new SqlServerProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOSqlsrv\Driver
     */
    protected function getDoctrineDriver()
    {
        return new DoctrineDriver;
    }
}
