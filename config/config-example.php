<?php

return [
    "databases" => [
        "local" => [
            "hostname" => "127.0.0.1",
            "port" => 3306,
            "username" => "user",
            "password" => "password",
            "databases" => ["*"]
        ],
    ],
    "destination" => [
        "path" => __DIR__ . '/../data/',
    ]

];
