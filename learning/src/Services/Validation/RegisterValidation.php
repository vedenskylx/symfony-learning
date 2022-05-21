<?php

namespace App\Services\Validation;

use App\Exception\ValidationException;
use App\Repository\UserRepository;
use App\Services\AbstractService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegisterValidation
 * @package App\Services\Validation
 */
final class RegisterValidation extends AbstractValidation
{
    /**
     * @param UserRepository $repository
     * @param ValidatorInterface $validator
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly UserRepository $repository,
        protected readonly ValidatorInterface $validator,
        LoggerInterface $logger
    ) {
        parent::__construct($logger, $validator);
    }

    /**
     * @param AbstractService $service
     * @param array $params
     * @return void
     */
    public function validateCreature(AbstractService $service, array $params): void
    {
        if ($params['password'] != $params['password_repeat']) {
            throw new ValidationException('Passwords must be equals!');
        }
    }

    public function validateUpdate(AbstractService $service, array $params)
    {
        // TODO: Implement validateUpdate() method.
    }
}
