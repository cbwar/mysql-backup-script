<?php


namespace Cbwar\MysqlBackup;


use Symfony\Component\Console\Output\OutputInterface;

class DatabaseDump
{

    private int $keepHistory = 10;

    /**
     * DumpServer constructor.
     */
    public function __construct(
        private array $configuration,
        private readonly string $destinationPath,
        private readonly OutputInterface $output,
        private readonly bool $compress = true
    ) {
    }

    /**
     * @return \PDO
     */
    private function getPdo()
    {
        $pdo = new \PDO('mysql:host=' . $this->configuration['hostname'] . ':'
            . $this->configuration['port'], $this->configuration['username'], $this->configuration['password']);
        return $pdo;
    }

    /**
     * Dump database
     * @return int
     */
    private function mysqldump(string $database, string $filename)
    {
        $return_var = NULL;
        $output = NULL;
        $errorFile = $filename . '.log';
        $command = sprintf(
            "/usr/bin/mysqldump --skip-lock-tables -u%s -h%s -P%d -p%s %s 2>%s >%s",
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
        $pdo = $this->getPdo();

        $result = $pdo->query('SHOW DATABASES')->fetchAll();
        return array_values(
            array_filter(array_map(fn ($item) => $item['Database'], $result), function ($item) {
                if (in_array($item, [
                    'information_schema',
                    'performance_schema',
                    'mysql',
                    'sys',
                ])) {
                    return false;
                }
                return true;
            })
        );
    }

    /**
     * Dump a database to a file
     * @param string|[] $database
     * @param string|null $filename
     * @throws DumpException
     * @throws \Exception
     */
    public function run($database, string $filename = null)
    {

        if ($database === '*' || (is_array($database) && $database[0] === '*')) {
            // All databases
            try {
                $databases = $this->findDatabases();
                $this->output->writeln(count($databases) . " databases found.");
                $this->run($databases);
            } catch (\PDOException $err) {
                throw new DumpException($err->getMessage());
            }
            return;
        }

        if (is_array($database)) {
            foreach ($database as $db) {
                try {
                    $this->run($db);
                    $this->output->writeln("<info>Dump ok</info>");
                } catch (DumpException $err) {
                    $this->output->writeln("<error>Dump error</error> : " . $err->getMessage());
                }
                $this->output->writeln('');
            }
            return;
        }

        if ($filename === null) {
            $filename = sprintf("%s-%s.sql", $database, date('YmdHis'));
        }
        $path = sprintf("%s/%s/%s", $this->destinationPath, $database, $filename);

        if (!@mkdir(dirname($path), 0777, true) && !is_dir(dirname($path))) {
            throw new \Exception("Cannot create destination path " . dirname($path));
        }

        if (is_file($path) || is_file($path . '.gz')) {
            $this->output->writeln("File $path already exists. Not dumping $database");
            return;
        }
        $this->output->writeln(" * Dumping database '$database' to '$path'");
        $return = $this->mysqldump($database, $path);

        if ($return !== 0) {
            throw new DumpException(sprintf("Error dumping database '%s', return code = %d", $database, $return));
        }

        if ($this->compress === true) {
            $return = $this->gzip($path);

            if ($return !== 0) {
                throw new DumpException(sprintf("Error compressing database '%s', return code = %d", $database, $return));
            }
        }

        $this->cleanup($database);
    }

    /**
     * @return int
     */
    private function gzip(string $filename)
    {
        $return_var = NULL;
        $output = NULL;
        $errorFile = $filename . '.log';
        $command = sprintf(
            "cat %s | gzip -c 2>%s >%s && rm -f %s",
            $filename,
            $errorFile,
            $filename . '.gz',
            $filename
        );
        exec($command, $output, $return_var);

        if ($return_var !== 0 && file_exists($filename)) {
            unlink($filename);
        }
        return $return_var;
    }

    /**
     * @param $history
     */
    private function cleanup($database)
    {
        if (isset($this->configuration['keep'])) {
            $history = $this->configuration['keep'];
        } else {
            $history = $this->keepHistory;
        }

        $this->output->writeln("Keeping $history files");

        $files = array_map(fn ($item) => new \SplFileObject($item), glob($this->destinationPath . '/' . $database . '/*.gz'));
        usort($files, function (\SplFileObject $a, \SplFileObject $b) {
            if ($a->getMTime() > $b->getMTime()) return -1;
            return 1;
        });
        $files = array_slice($files, $history);

        /** @var \SplFileObject $file */
        foreach ($files as $file) {

            $logfile = $file->getPath() . '/' . pathinfo($file->getFilename(), PATHINFO_FILENAME) . '.log';

            $this->output->writeln("Removing {$file->getRealPath()}");
            unlink($file->getRealPath());

            // TODO: Delete log file
            $this->output->writeln("Removing $logfile");
            unlink($logfile);
        }
    }

    public function setKeepHistory(int $keepHistory)
    {
        $this->keepHistory = $keepHistory;
    }
}
