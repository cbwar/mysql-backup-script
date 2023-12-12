<?php

use Cbwar\MysqlBackup\Command\ConfigDump;
use Cbwar\MysqlBackup\Command\ConfigTest;
use Cbwar\MysqlBackup\Command\Run;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';


$app = new Application('cbwar/mysql-backup-script', '1.0');
$app->add(new ConfigDump());
$app->add(new ConfigTest());
$app->add(new Run());
$app->run();
