<?php

namespace Tests;

use Cbwar\MysqlBackup\ConfigReader;
use Generator;
use PHPUnit\Framework\TestCase;

class ConfigReaderTest extends TestCase
{

    public static function configFileProvider(): Generator
    {
        yield "valid: single server all databases" => [
            __DIR__ . '/fixtures/config_example_single_server_all_databases.php', 0
        ];

        yield "invalid: missing hostname" => [
            __DIR__ . '/fixtures/config_example_missing_hostname.php', 1
        ];

        //TODO: more tests
    }

    /**
     * @dataProvider configFileProvider
     */
    public function testValidate(string $filename, int $errors)
    {
        $reader = new ConfigReader($filename);
        $violations = $reader->validate();
        self::assertCount($errors, $violations);
    }


}
