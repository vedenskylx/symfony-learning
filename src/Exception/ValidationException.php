<?php

namespace App\Exception;

use Doctrine\ORM\Mapping\Entity;
use PhpParser\Builder\Class_;

class ValidationException extends \RuntimeException
{
    const VALIDATION_ERROR_TEMPLATE = "Validation error: %s";

    public function __construct(string $error)
    {
        parent::__construct(sprintf(self::VALIDATION_ERROR_TEMPLATE, $error), 422);
    }
}
