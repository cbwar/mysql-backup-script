<?php

return [
    "databases" => [
        "local" => [
            "hostname" => "127.0.0.1",
            "port" => 3306,
            "username" => "raf",
            "password" => "GElaDOvhyVT8k",
            "databases" => ["*"]
        ],
        "err" => [
            "hostname" => "127.0.0.1",
            "port" => 3308,
            "username" => "raf",
            "password" => "GElaDOvhyVT8k",
            "databases" => ["test"]
        ],
        "orion_pub" => [
            "hostname" => "orion",
            "port" => 3307,
            "username" => "raf",
            "password" => "AGaqDNxP.DJ2k",
            "databases" => ["perso_dev", "shop_dev", "temp"]
        ]
    ],
    "destination" => [
        "path" => __DIR__ . '/../data/',
    ]

];
