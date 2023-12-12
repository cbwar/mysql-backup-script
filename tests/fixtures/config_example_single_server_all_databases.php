<?php

return [
    "keep" => '30',
    "compress" => true,
    "servers" => [
        "local" => [
            "hostname" => "127.0.0.1",
            "port" => '3306',
            "username" => "backup",
            "password" => "xxxx",
            "databases" => ["*"],
        ],
    ],
    "destination" => [
        "path" => '/home/backups/databases',
    ]

];
