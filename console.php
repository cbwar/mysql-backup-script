<?php

require __DIR__ . '/vendor/autoload.php';


$app = new \Symfony\Component\Console\Application('cbwar/mysql-backup-script', '1.0');
$app->add(new \Cbwar\MysqlBackup\Command\ConfigDump());
$app->add(new \Cbwar\MysqlBackup\Command\ConfigTest());
$app->add(new \Cbwar\MysqlBackup\Command\Run());
$app->run();
