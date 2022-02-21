<?php

namespace Cbwar\MysqlBackup;

use Cbwar\MysqlBackup\Exception\InvalidConfigurationException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ConfigReader
{

    /**
     * @throws InvalidConfigurationException
     */
    public function read(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new InvalidConfigurationException('Configuration error: filename ' . $filename . ' not found.');
        }
        $config = require $filename;
        $this->validate($config);
        return $config;
    }

    /**
     * @throws InvalidConfigurationException
     */
    protected function validate(array $config): void
    {
        $validator = Validation::createValidator();

        $constraint = new Assert\Collection([
            'compress' => new Assert\Choice([true, false]),
            'keep' => new Assert\Range(['min' => 1]),
            'servers' => new Assert\All([
                'constraints' => new Assert\Collection([
                    "hostname" => new Assert\Length(['min' => 1]),
                    "port" => new Assert\Required(),
                    "username" => new Assert\Required(),
                    "password" => new Assert\Required(),
                    "databases" => new Assert\Collection([]),
                    "keep" => new Assert\Optional()
                ]),
            ]),
            'destination' => new Assert\Collection([
                'path' => new Assert\Length(['min' => 1])
            ])
        ]);

        $violations = $validator->validate($config, $constraint);
        if (count($violations) > 0) {
            $violation = $violations[0];
            throw new InvalidConfigurationException('Configuration error: ' . $violation->getPropertyPath() . ' : ' . $violation->getMessage());
        }
    }

}
