<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/11/16
 * Time: 1:39
 */


namespace think\laravel\command\migrate;

use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\laravel\command\LaravelCommand;

class Install extends LaravelCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('jntoo:install')
            ->setDescription('Create the migration repository')
            ->setHelp(
                <<<EOT
                Create the migration repository
EOT
            );
    }

    /**
     * Migrate the database.
     *
     * @param Input $input
     * @param Output $output
     * @return integer integer 0 on success, or an error code.
     */
    protected function execute(Input $input, Output $output)
    {
        $this->bootstrap($input, $output);
        $this->repository->createRepository();
        return 0;
    }

    /**
     * Get an array of all of the migration files.
     *
     * @return array
     */
    protected function getAllMigrationFiles()
    {
        return $this->migrator->getMigrationFiles($this->getMigrationPaths());
    }
}