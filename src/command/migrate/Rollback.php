<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/10/28
 * Time: 1:53
 */



namespace think\laravel\command\migrate;

use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;
use Laravel\Migrations\Migrator;
use think\laravel\command\LaravelCommand;

class Rollback extends LaravelCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('jntoo:rollback')
            ->setDescription('Rollback the last database migration')
            ->addOption('module', 'm', InputOption::VALUE_OPTIONAL, 'Select Modules Path')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production.')
            ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.')
            ->addOption('step', null, InputOption::VALUE_OPTIONAL, 'The number of migrations to be reverted.')
            ->setHelp(
                'Rollback the last database migration'
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

        $this->migrator->rollback(
            $this->getMigrationPaths(), ['pretend' => $this->option('pretend'), 'step' => (int) $this->option('step')]
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