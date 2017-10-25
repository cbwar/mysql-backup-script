<?php

namespace Cbwar\MysqlBackup\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigDump extends Command
{
    protected function configure()
    {
        $this
            ->setName('config-dump')
            ->setDescription('Dump the configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('config-test');
        $testInput = new ArrayInput(['command' => 'config-test']);
        $returnCode = $command->run($testInput, $output);
        if ($returnCode !== 0) {
            return $returnCode;
        }

        $config = require __DIR__ . '/../../config/config.php';

        $output->writeln('');
        $output->writeln('<options=bold>Servers</>');
        foreach ($config['servers'] as $key => $database) {
            $output->writeln(' - ' . $key);
            foreach ($database as $key2 => $value) {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                if ($key2 === 'password') {
                    $value = '*********';
                }
                if ($key2 === 'databases' && $value === '*') {
                    $value = 'ALL DATABASES';
                }
                $output->writeln('      - ' . $key2 . ' => ' . $value);
            }
        }

        $output->writeln('');
        $output->writeln('<options=bold>Destination</>');
        $output->writeln(' Local Path => ' . realpath($config['destination']['path']));

        return 0;
    }
}