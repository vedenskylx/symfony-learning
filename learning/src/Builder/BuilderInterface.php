<?php

namespace App\Builder;

use App\Entity\EntityInterface;

interface BuilderInterface
{
    public function build(array $params): EntityInterface;
}
