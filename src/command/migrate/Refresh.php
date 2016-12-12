<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/10/28
 * Time: 1:50
 */

namespace think\laravel\command\migrate;

use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;
use Laravel\Migrations\Migrator;
use think\laravel\command\LaravelCommand;

class Refresh extends LaravelCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('jntoo:refresh')
            ->setDescription('Reset and re-run all migrations')
            ->addOption('module', 'm', InputOption::VALUE_REQUIRED, 'Select Modules Path，default common')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production.')
            ->addOption('seed', null, InputOption::VALUE_REQUIRED, 'Indicates if the seed task should be re-run.')
            ->addOption('seeder', null, InputOption::VALUE_REQUIRED, 'The class name of the root seeder.')
            ->addOption('step', null, InputOption::VALUE_REQUIRED, 'Force the migrations to be run so they can be rolled back individually.')
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

        $force = $this->input->getOption('force');

        $path = $this->input->getOption('path');

        // If the "step" option is specified it means we only want to rollback a small
        // number of migrations before migrating again. For example, the user might
        // only rollback and remigrate the latest four migrations instead of all.
        $step = $this->input->getOption('step') ?: 0;

        if ($step > 0) {
            $this->call('jntoo:rollback', [
                '--force' => $force, '--step' => $step,
            ]);
        } else {
            $this->call('jntoo:reset', [
                 '--force' => $force
            ]);
        }

        // The refresh command is essentially just a brief aggregate of a few other of
        // the migration commands and just provides a convenient wrapper to execute
        // them in succession. We'll also see if we need to re-seed the database.
        $this->call('jntoo:run', [
            '--force' => $force
        ]);

        if ($this->needsSeeding()) {
            $this->runSeeder();
        }

        return 0;
    }

    /**
     * Determine if the developer has requested database seeding.
     *
     * @return bool
     */
    protected function needsSeeding()
    {
        return $this->option('seed') || $this->option('seeder');
    }

    /**
     * Run the database seeder command.
     *
     * @param  string  $database
     * @return void
     */
    protected function runSeeder()
    {
        $class = $this->option('seeder') ?: 'DatabaseSeeder';

        $force = $this->input->getOption('force');

        $this->call('jntoo:seed', [
             '--class' => $class, '--force' => $force,
        ]);
    }
}
