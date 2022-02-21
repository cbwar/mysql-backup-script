<?php

namespace Cbwar\MysqlBackup\Command;

use Cbwar\MysqlBackup\ConfigReader;
use Cbwar\MysqlBackup\Exception\InvalidConfigurationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigTest extends Command
{
    protected function configure()
    {
        $this
            ->setName('config-test')
            ->setDescription('Test the configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = __DIR__ . '/../../config/config.php';
        if (file_exists(__DIR__ . '/../../config/config.local.php')) {
            $configFile = __DIR__ . '/../../config/config.local.php';
        }
        $output->writeln(sprintf("<info>Checking file %s</info>", realpath($configFile)));
        $reader = new ConfigReader();
        try {
            $reader->read($configFile);
        } catch (InvalidConfigurationException $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return 1;
        }

        $output->writeln("<info>Configuration is correct.</info>");
        return 0;
    }

}
