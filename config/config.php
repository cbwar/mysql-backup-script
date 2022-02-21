<?php

return [
    "keep" => 5, // Files to keep (default value)
    "compress" => true, // Compress SQL files
    "servers" => [
//        "local" => [
//            "hostname" => "127.0.0.1",
//            "port" => 3306,
//            "username" => "user",
//            "password" => "password",
//            "databases" => ["*"],
//        ],
//        "remote_server" => [
//            "hostname" => "mysqlserver.example.com",
//            "port" => 3350,
//            "username" => "user",
//            "password" => "password",
//            "databases" => ["db1", "db2"],
//            "keep" => 12, // Files to keep (this server)
//        ],
    ],
    "destination" => [
        "path" => __DIR__ . '/../backups', // Dumps destination, create subdirectories per server & per database
    ]
];
