<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/11/15
 * Time: 23:26
 */


namespace think\laravel\command\migrate;

use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\laravel\command\LaravelCommand;

class Status extends LaravelCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('jntoo:status')
            ->setDescription('Show the status of each migration')
            ->addOption('module', 'm', InputOption::VALUE_NONE, 'Select Modules Path')
            ->setHelp(
                <<<EOT
                Show the status of each migration
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
        if (! $this->migrator->repositoryExists()) {
            $this->error('No migrations found.');
            return 0;
        }
        $ran = $this->migrator->getRepository()->getRan();

        $migrations = Collection::make($this->getAllMigrationFiles())
            ->map(function ($migration) use ($ran) {
                return in_array($this->migrator->getMigrationName($migration), $ran)
                    ? ['<info>Y</info>', $this->migrator->getMigrationName($migration)]
                    : ['<fg=red>N</fg=red>', $this->migrator->getMigrationName($migration)];
            });

        if (count($migrations) > 0) {
            $this->table(['Ran?', 'Migration'], $migrations);
        } else {
            $this->error('No migrations found');
        }

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