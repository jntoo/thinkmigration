<?php
// +----------------------------------------------------------------------
// | TopThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhangyajun <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\laravel\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option as InputOption;
use think\console\Output;

abstract class LaravelCommand extends Command
{
    protected $config;


    /**
     * @var Manager
     */
    protected $manager;

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
        if (!$this->getConfig()) {
            $this->loadConfig($input, $output);
        }

        $this->loadManager($output);
        // report the paths
        $output->writeln('<info>using migration path</info> ' . $this->getConfig()->getMigrationPath());
        try {
            $output->writeln('<info>using seed path</info> ' . $this->getConfig()->getSeedPath());
        } catch (\UnexpectedValueException $e) {
            // do nothing as seeds are optional
        }
    }

    /**
     * Sets the config.
     *
     * @param  Config $config
     * @return AbstractCommand
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Gets the config.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Parse the config file and load it into the config object
     *
     * @param Input $input
     * @param Output $output
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function loadConfig(Input $input, Output $output)
    {
        $configFile = APP_PATH . 'database' . EXT;

        if (!is_file($configFile)) {
            throw new InvalidArgumentException();
        }

        $output->writeln('<info>using config file</info> .' . str_replace(getcwd(), '', realpath($configFile)));
        $config =  include($configFile);
        $this->setConfig($config);
    }

}
