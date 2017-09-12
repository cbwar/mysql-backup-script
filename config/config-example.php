<?php

return [
    "keep" => 2,
    "compress"=>true,
    "databases" => [
        "local" => [
            "hostname" => "127.0.0.1",
            "port" => 3306,
            "username" => "user",
            "password" => "password",
            "databases" => ["*"],
            "keep" => 5
        ],
    ],
    "destination" => [
        "path" => __DIR__ . '/../data/',
    ]

];
