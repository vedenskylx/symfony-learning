<?php

namespace App\Services;

use App\Builder\User as UserBuilder;
use App\Repository\UserRepository;
use App\Services\Validation\RegisterValidation;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class User extends AbstractService
{
    public function __construct(
        UserRepository $repository,
        EntityManagerInterface $entityManager,
        UserBuilder $builder,
        LoggerInterface $logger,
        RegisterValidation $validator
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($builder, $validator, $repository, $entityManager, $logger);
    }
}
