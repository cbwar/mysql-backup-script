<?php


namespace Cbwar\MysqlBackup;


use Symfony\Component\Console\Output\OutputInterface;

class DatabaseDump
{

    /**
     * @var array
     */
    private $configuration;
    /**
     * @var string
     */
    private $destinationPath;
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * DumpServer constructor.
     * @param array $configuration
     * @param string $destinationPath
     */
    public function __construct(array $configuration, string $destinationPath, OutputInterface $output)
    {
        $this->configuration = $configuration;
        $this->destinationPath = $destinationPath;
        $this->output = $output;

        if (!@mkdir($destinationPath, 0777, true) && !is_dir($destinationPath)) {
            throw new \Exception("Cannot create destination path $destinationPath");
        }

    }


    /**
     * Dump database
     * @param string $database
     * @param string $filename
     */
    private function mysqldump(string $database, string $filename)
    {
        $return_var = NULL;
        $output = NULL;
        $errorFile = $filename . '.log';
        $command = sprintf("/usr/bin/mysqldump -u%s -h%s -P%d -p%s %s 2>%s >%s",
            $this->configuration['username'],
            $this->configuration['hostname'],
            $this->configuration['port'],
            $this->configuration['password'],
            $database,
            $errorFile,
            $filename
        );
        exec($command, $output, $return_var);
        if ($return_var !== 0 && file_exists($filename)) {
            unlink($filename);
        }
        return $return_var;
    }

    /**
     * @return []
     */
    private function findDatabases()
    {


        return [];
    }

    /**
     * Dump a database to a file
     * @param string|[] $database
     * @param string|null $filename
     */
    public function run($database, ?string $filename = null)
    {
        if (is_array($database)) {
            foreach ($database as $db) {
                try {
                    $this->run($db);
                    $this->output->writeln("<info>Dump ok</info>");
                } catch (DumpException $err) {
                    $this->output->writeln("<error>Dump error, see logfile</error>");
                }
            }
            return;
        }

        if ($database === '*') {
            $this->run($this->findDatabases());
            return;
        }

        if ($filename === null) {
            $filename = sprintf("%s-%s.sql", $database, date('YmdHis'));
        }
        $path = sprintf("%s/%s", $this->destinationPath, $filename);
        $this->output->writeln("Dumping database '$database' to '$path'");
        $return = $this->mysqldump($database, $path);

        if ($return !== 0) {
            throw new DumpException(sprintf("Error dumping database '%s', return code = %d", $database, $return));
        }
    }

}