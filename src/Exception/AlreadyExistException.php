<?php

namespace App\Exception;

use Doctrine\ORM\Mapping\Entity;
use PhpParser\Builder\Class_;

class AlreadyExistException extends \RuntimeException
{
    const ALREADY_EXIST_ERROR_TEMPLATE = "Validation error: %s %s already exist!";

    public function __construct(string $entity, string $title)
    {
        parent::__construct(sprintf(self::ALREADY_EXIST_ERROR_TEMPLATE, $entity, $title), 422);
    }
}
