<?php

namespace Cbwar\MysqlBackup\Command;

use Cbwar\MysqlBackup\ConfigReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigTest extends Command
{
    protected static $defaultName = 'config-test';
    protected function configure()
    {
        $this
            ->setDescription('Test the configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = __DIR__ . '/../../config/config.php';

        $output->writeln(sprintf("<info>Checking file %s</info>", realpath($configFile)));
        $reader = new ConfigReader($configFile);
        $violations = $reader->validate();

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $output->writeln("<error>Configuration error: " . $violation->getPropertyPath() . " : " . $violation->getMessage() . "</error>");
            }
            return 1;
        }

        $output->writeln("<info>Configuration is correct.</info>");
        return 0;
    }

}