<?php

namespace Cbwar\MysqlBackup;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ConfigReader
{

    /**
     * ConfigReader constructor.
     */
    public function __construct(private readonly string $filename)
    {
    }

    /**
     * @return ConstraintViolation[]
     * @throws \Exception
     */
    public function validate()
    {
        if (!file_exists($this->filename)) {
            throw new \Exception("Configuration file not found");
        }

        $config = require $this->filename;

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
                    "keep"=>new Assert\Optional()
                ]),
            ]),
            'destination' => new Assert\Collection([
                'path' => new Assert\Length(['min' => 1])
            ])
        ]);
        return $validator->validate($config, $constraint);
    }

}