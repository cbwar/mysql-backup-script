# Mysql Backup Script 

## Install

```bash
git clone https://github.com/cbwar/mysql-backup-script.git
cd mysql-backup-script
make
```


##Configure

```bash
cp config/config-example.php config/config.php
 ```
 
```php
<?php

return [
    "keep" => 5, // Files to keep (default value)
    "compress" => true, // Compress SQL files
    "servers" => [
        "local" => [
            "hostname" => "127.0.0.1",
            "port" => 3306,
            "username" => "user",
            "password" => "password",
            "databases" => ["*"],
        ],
        "remote_server" => [
            "hostname" => "mysqlserver.example.com",
            "port" => 3350,
            "username" => "user",
            "password" => "password",
            "databases" => ["db1", "db2"],
            "keep" => 12, // Files to keep (this server)
        ],
    ],
    "destination" => [
        "path" => '/mnt/backups/databases', // Dumps destination, create subdirectories per server & per database
    ]

];                                      
```

## Run
```bash
php console.php
```
```
Available commands:
  config-dump  Dump the configuration
  config-test  Test the configuration
  help         Displays help for a command
  list         Lists commands
  run          Execute backup
```