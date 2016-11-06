<?php



namespace laravel;
use Laravel\Schema\Builder;
use Laravel\App;
class Schema
{
    /**
     * @var \Laravel\Schema\Builder
     */
    static public function __callStatic( $methods , $args )
    {
        return self::call($methods , $args);
    }

    static public function call( $methods , $args = array())
    {
        $bu = App::db()->getSchemaBuilder();

        if(count($args)>0){
            return call_user_func_array(array($bu, $methods) , $args);
        }else{
            return call_user_func(array($bu , $methods));
        }
    }


    /**
     * Determine if the given table exists.
     *
     * @param  string  $table
     * @return bool
     */
    static public function hasTable($table)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Determine if the given table has a given column.
     *
     * @param  string  $table
     * @param  string  $column
     * @return bool
     */
    static public function hasColumn($table, $column)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Determine if the given table has given columns.
     *
     * @param  string  $table
     * @param  array   $columns
     * @return bool
     */
    static public function hasColumns($table, array $columns)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Get the data type for the given column name.
     *
     * @param  string  $table
     * @param  string  $column
     * @return string
     */
    static public function getColumnType($table, $column)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Get the column listing for a given table.
     *
     * @param  string  $table
     * @return array
     */
    static public function getColumnListing($table)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Modify a table on the schema.
     *
     * @param  string    $table
     * @param  \Closure  $callback
     * @return \Laravel\Schema\Blueprint
     */
    static public function table($table, Closure $callback)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Create a new table on the schema.
     *
     * @param  string    $table
     * @param  \Closure  $callback
     * @return \Laravel\Schema\Blueprint
     */
    static public function create($table, $callback)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Drop a table from the schema.
     *
     * @param  string  $table
     * @return \Laravel\Schema\Blueprint
     */
    static public function drop($table)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Drop a table from the schema if it exists.
     *
     * @param  string  $table
     * @return \Laravel\Schema\Blueprint
     */
    static public function dropIfExists($table)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Rename a table on the schema.
     *
     * @param  string  $from
     * @param  string  $to
     * @return \Laravel\Schema\Blueprint
     */
    static public function rename($from, $to)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Enable foreign key constraints.
     *
     * @return bool
     */
    static public function enableForeignKeyConstraints()
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Disable foreign key constraints.
     *
     * @return bool
     */
    static public function disableForeignKeyConstraints()
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Get the database connection instance.
     *
     * @return \Laravel\Connection\Connection
     */
    static public function getConnection()
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Set the database connection instance.
     *
     * @param  \Laravel\Connection\Connection  $connection
     * @return $this
     */
    static public function setConnection(Connection $connection)
    {
        return self::call(__FUNCTION__ , func_get_args());
    }

    /**
     * Set the Schema Blueprint resolver callback.
     *
     * @param  \Closure  $resolver
     * @return void
     */
    static public function blueprintResolver(Closure $resolver)
    {
        self::call(__FUNCTION__ , func_get_args());
    }

}