<?php


namespace Cbwar\MysqlBackup\Command;


use Cbwar\MysqlBackup\DatabaseDump;
use Cbwar\MysqlBackup\DumpException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Run extends Command
{
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Execute backup');
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

        foreach ($config['databases'] as $key => $database) {
            $destination = rtrim($config['destination']['path'], '/') . '/' . $key;

            $dumper = new DatabaseDump($database, $destination, $output, $config['compress']);
            $output->writeln('');
            $output->writeln("<options=bold,underscore>Dumping " . $database['username'] . '@'
                . $database['hostname'] . ':' . $database['port'] . '</>');

            try {
                $dumper->run($database['databases']);
            } catch (DumpException $err) {

            }
        }

        return 0;
    }


}