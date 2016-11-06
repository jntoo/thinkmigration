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

class Migrate extends LaravelCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('migrate')
            ->setDescription('Run Migration')
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

        $version     = $input->getOption('target');
        $date        = $input->getOption('date');

        $dbConfig = $this->config->getDbConfig();
        
        if (isset($dbConfig['adapter'])) {
            $output->writeln('<info>using adapter</info> ' . $dbConfig['adapter']);
        }

        if (isset($dbConfig['name'])) {
            $output->writeln('<info>using database</info> ' . $dbConfig['name']);
        } else {
            $output->writeln('<error>Could not determine database name! Please specify a database name in your config file.</error>');
            return 1;
        }

        if (isset($dbConfig['table_prefix'])) {
            $output->writeln('<info>using table prefix</info> ' . $dbConfig['table_prefix']);
        }


        // run the migrations
        $start = microtime(true);
        if (null !== $date) {
            $this->getManager()->migrateToDateTime(new \DateTime($date));
        } else {
            $this->getManager()->migrate($version);
        }
        $end = microtime(true);

        $output->writeln('');
        $output->writeln('<comment>All Done. Took ' . sprintf('%.4fs', $end - $start) . '</comment>');

        return 0;
    }
}
