<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/10/28
 * Time: 1:51
 */


namespace think\laravel\command\migrate;

use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\laravel\command\LaravelCommand;

class Reset extends LaravelCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('jntoo:reset')
            ->setDescription('Rollback all database migrations')
            ->addOption('module', 'm', InputOption::VALUE_REQUIRED, 'Select Modules Path')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production.')
            ->addOption('pretend', null, InputOption::VALUE_REQUIRED, 'Dump the SQL queries that would be run.')
            ->setHelp(
                <<<EOT
                Rollback all database migrations
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
        if (! $this->confirmToProceed()) {
            return;
        }
        
        // First, we'll make sure that the migration table actually exists before we
        // start trying to rollback and re-run all of the migrations. If it's not
        // present we'll just bail out with an info message for the developers.
        if (! $this->migrator->repositoryExists()) {
            return $this->comment('Migration table not found.');
        }
        
        $this->migrator->reset(
            $this->getMigrationPaths(), $this->option('pretend')
        );

        // Once the migrator has run we will grab the note output and send it out to
        // the console screen, since the migrator itself functions without having
        // any instances of the OutputInterface contract passed into the class.
        foreach ($this->migrator->getNotes() as $note) {
            $this->output->writeln($note);
        }
        return 0;
    }
}
