<?php

namespace App\Services\Validation;

use App\Repository\PostRepository;
use App\Services\AbstractService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PostValidation
 * @package App\Services\Validation
 */
final class PostValidation extends AbstractValidation
{
    /**
     * @param PostRepository $repository
     * @param ValidatorInterface $validator
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly PostRepository $repository,
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
        // TODO: Implement validateUpdate() method.
    }

    public function validateUpdate(AbstractService $service, array $params)
    {
        // TODO: Implement validateUpdate() method.
    }
}
