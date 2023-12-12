<?php

return [
    "keep" => '30',
    "compress" => true,
    "servers" => [
        "local" => [
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
