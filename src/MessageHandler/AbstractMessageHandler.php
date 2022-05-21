<?php

namespace App\MessageHandler;

use App\Builder\BuilderInterface;
use App\Services\AbstractService;

abstract class AbstractMessageHandler
{
    /**
     * AbstractMessageHandler constructor.
     *
     * @param AbstractService $service
     * @param BuilderInterface $builder
     */
    public function __construct(
        protected readonly AbstractService $service,
        protected readonly BuilderInterface $builder
    ) {
    }
}
