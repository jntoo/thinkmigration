<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/10/28
 * Time: 1:47
 */

namespace think\laravel\command\make;


use think\console\input\Argument as InputArgument;
use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\laravel\command\LaravelCommand;
use Laravel\Migrations\MigrationCreator;

class Migration extends LaravelCommand
{
    /**
     * @var \Laravel\Migrations\MigrationCreator;
     */
    protected $creator;
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('jntoo:create')
            ->setDescription('Create a new migration')
            ->addArgument('name', InputArgument::REQUIRED, 'What is the name of the migration?')
            ->addOption('module', 'm', InputOption::VALUE_NONE, 'APP_PATH module to create a new database migration.')
            ->addOption('create' , 'c' , InputOption::VALUE_NONE , 'The table to be created.')
            ->addOption('table' , 't' , InputOption::VALUE_NONE , 'The table to migrate.')
            ->setHelp(sprintf(
                '%sCreate a new migration file%s',
                PHP_EOL,
                PHP_EOL
            ));
    }

    /**
     * Create the new migration.
     *
     * @param Input  $input
     * @param Output $output
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $this->bootstrap($input, $output);

        $this->creator = new MigrationCreator($this->filesystem);
        $path = $this->getMigrationPath();
        $name = trim($input->getArgument('name'));
        $table = $input->getOption('table') ? $input->getOption('create') : null;
        $create = $input->getOption('create') ? $input->getOption('create') : false;

        if (! $table && is_string($create)) {
            $table = $create;
            $create = true;
        }
        if(!$table && stripos($name , 'create_') === 0)
        {
            // 是创建表
            $table = substr($name , 7);
            $create = true;
        }
        
        if(!$table && stripos($name , 'update_') === 0)
        {
            // 是更新表
            $table = substr($name , 7);
        }

        $file = pathinfo($this->creator->create($name , $path, $table ,$create ) , PATHINFO_FILENAME);

        $output->writeln("<info>Created Migration:</info> {$file}");
    }
}
