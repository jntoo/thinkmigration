<?php
// +----------------------------------------------------------------------
// | TopThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhangyajun <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\laravel\command;

use laravel\Schema;
use think\console\Command;
use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\Console;
use Laravel\Connection\Connection;
use Laravel\App;

use Laravel\Migrations\Migrator;
use Laravel\Filesystem\Filesystem;
use Laravel\Migrations\DatabaseMigrationRepository;
use library\sysfony\console\Table;


use Laravel\Contracts\Support\Arrayable;


abstract class LaravelCommand extends Command
{
    protected $config;
    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var Filesystem;
     */
    protected $filesystem;
    /**
     * @var \Laravel\Migrations\Migrator
     */
    protected $migrator;
    /**
     * @var \Laravel\Migrations\DatabaseMigrationRepository;
     */
    protected $repository;

    /**
     * The mapping between human readable verbosity levels and Symfony's OutputInterface.
     *
     * @var array
     */
    protected $verbosityMap = [
        'v'      => Output::VERBOSITY_VERBOSE,
        'vv'     => Output::VERBOSITY_VERY_VERBOSE,
        'vvv'    => Output::VERBOSITY_DEBUG,
        'quiet'  => Output::VERBOSITY_QUIET,
        'normal' => Output::VERBOSITY_NORMAL,
    ];

    /**
     * The default verbosity of output commands.
     *
     * @var int
     */
    protected $verbosity = 0;


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {

    }

    /**
     * Bootstrap Phinx.
     *
     * @param Input $input
     * @param Output $output
     * @return void
     */
    public function bootstrap(Input $input, Output $output)
    {
        $this->repository  = new DatabaseMigrationRepository(App::resolver() , 'jntoomigration');
        $this->filesystem = new Filesystem();
        $this->migrator = new Migrator($this->repository , App::resolver() , $this->filesystem);
        // report the paths
    }

    protected function getModule()
    {
        return $this->input->getOption('module');
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        $module = $this->getModule('module');

        $module or $module = 'common';
        $this->output->writeln($module);
        $dir = APP_PATH.$module.'/database/migrations';
        if(!is_dir($dir)){
            @mkdir(APP_PATH.$module.'/database');
            @mkdir(APP_PATH.$module.'/database/migrations');
        }
        return $dir;
    }

    protected function option( $key )
    {
        return $this->input->getOption($key);
    }

    protected function call($cmd ,array $option)
    {
        Console::call($cmd , $option);
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        if (! $this->migrator->repositoryExists()) {
            $options = [];
            $this->call('jntoo:install', $options);
        }
    }
    
    protected function getMigrationPaths()
    {
        $result = [];
        $module = $this->getModule();
        if($module){
            $result[] = APP_PATH . $module .'/database/migrations';
        }else{
            $dirs = $this->filesystem->directories(APP_PATH);


            foreach ($dirs as $dir)
            {
                if(is_dir($dir . '/database/migrations') )
                {
                    $result[] = $dir . '/database/migrations';
                }
            }
        }
        return $result;
    }

    /**
     * Write a string as standard output.
     *
     * @param  string  $string
     * @param  string  $style
     * @param  null|int|string  $verbosity
     * @return void
     */
    public function line($string, $style = null, $verbosity = null)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;

        $this->output->writeln($styled, $this->parseVerbosity($verbosity));
    }



    /**
     * Get the verbosity level in terms of Symfony's OutputInterface level.
     *
     * @param  string|int  $level
     * @return int
     */
    protected function parseVerbosity($level = null)
    {
        if (isset($this->verbosityMap[$level])) {
            $level = $this->verbosityMap[$level];
        } elseif (! is_int($level)) {
            $level = $this->verbosity;
        }
        return $level;
    }
    /**
     * Write a string as comment output.
     *
     * @param  string  $string
     * @param  null|int|string  $verbosity
     * @return void
     */
    public function comment($string, $verbosity = null)
    {
        $this->line($string, 'comment', $verbosity);
    }


    /**
     * Confirm before proceeding with the action.
     *
     * @param  string    $warning
     * @param  \Closure|bool|null  $callback
     * @return bool
     */
    protected function confirmToProceed($warning = 'Application In Production!')
    {
        if ($this->option('force')) {
            return true;
        }
        $this->comment(str_repeat('*', strlen($warning) + 12));
        $this->comment('*     '.$warning.'     *');
        $this->comment(str_repeat('*', strlen($warning) + 12));
        $this->output->writeln('');
        $confirmed = $this->confirm('Do you really wish to run this command?');
        if (! $confirmed) {
            $this->comment('Cancel Success！');
            return false;
        }
        return true;
    }

    protected function confirm($question, $default = true)
    {
        return $this->output->confirm($this->input , $question , $default);
    }

    /**
     * Format input to textual table.
     *
     * @param  array   $headers
     * @param  array  $rows
     * @param  string  $style
     * @return void
     */
    public function table(array $headers, $rows, $style = 'default')
    {
        $table = new Table($this->output);

        if ($rows instanceof Arrayable) {
            $rows = $rows->toArray();
        }

        $table->setHeaders($headers)->setRows($rows)->setStyle($style)->render();
    }

    /**
     * Write a string as error output.
     *
     * @param  string  $string
     * @param  null|int|string  $verbosity
     * @return void
     */
    public function error($string, $verbosity = null)
    {
        $this->line($string, 'error', $verbosity);
    }
}
