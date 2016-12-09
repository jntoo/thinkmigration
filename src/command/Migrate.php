<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/10/28
 * Time: 1:50
 */

namespace think\laravel\command;

use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\migration\command\AbstractCommand;
use Laravel\Migrations\Migrator;
use think\laravel\command\LaravelCommand;

class Migrate extends LaravelCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('jntoo:run')
            ->setDescription('Run Migration')
            ->addOption('module', 'm', InputOption::VALUE_OPTIONAL, 'Select Modules Path，default common')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production.')
            ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.')
            ->addOption('seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.')
            ->addOption('step', null, InputOption::VALUE_NONE, 'Force the migrations to be run so they can be rolled back individually.')
            ->setHelp(
                <<<EOT
                The <info>migrate</info> command runs all available migrations, optionally up to a specific version
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
        $this->prepareDatabase();
        
        $this->migrator->run($this->getMigrationPaths(), [
            'pretend' => $this->option('pretend'),
            'step' => $this->option('step'),
            'module'=>$this->getModule()
        ]);
        
        foreach ($this->migrator->getNotes() as $note) {
            $this->output->writeln($note);
        }
        // Finally, if the "seed" option has been given, we will re-run the database
        // seed task to re-populate the database, which is convenient when adding
        // a migration and a seed at the same time, as it is only this command.
        if ($this->option('seed')) {
            $this->line('暂时不支持seed');
            //$this->call('db:seed', ['--force' => true]);
        }
        return 0;
    }
}
