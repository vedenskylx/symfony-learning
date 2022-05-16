<?php

namespace App\Services\Validation;

use App\Entity\EntityInterface;
use App\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\{ConstraintViolation, ConstraintViolationList};
use App\Services\AbstractService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidation
{

    /**
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     * @param array $errors
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        ValidatorInterface $validator,
        protected $errors = []
    ) {
    }

    /**
     * @param EntityInterface $entity
     * @return $this
     */
    public function entityValidation(EntityInterface $entity): static
    {
        /** @var ConstraintViolationList $errors */
        $this->errors = $this->validator->validate($entity);

        if (count($this->errors)) {
            $errorMessages = $this->getFullMessages($this->errors);
            $this->logger->error('Entity validation error', $errorMessages);
            throw new ValidationException(current($errorMessages));
        }

        return $this;
    }

    /**
     * @param $errors
     * @return array
     */
    private function getFullMessages($errors): array
    {
        $errorMessages = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $errorMessages[] = sprintf(
                '%s: %s',
                $error->getPropertyPath(),
                $error->getMessage()
            );
        }

        return $errorMessages;
    }

    /**
     * @param AbstractService $service
     * @param $entityId
     * @return $this
     */
    public function entityExists(AbstractService $service, $entityId): AbstractValidation
    {

        if (!$service->find($entityId)) {
            $this->errors[] = 'Entity ' . $entityId . ' doesn\'t exists in service ' . get_class($service);
        }

        return $this;
    }

    /**
     * @param AbstractService $service
     * @param $entityId
     * @return AbstractValidation
     */
    public function entityNotExists(AbstractService $service, $entityId): AbstractValidation
    {
        if ($service->find($entityId)) {

            $this->errors[] = 'Entity ' . $entityId . '  exists in service ' . get_class($service);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function passed(): bool
    {

        return (bool)!$this->errors;
    }

    /**
     * @return bool
     */
    public function failed(): bool
    {

        return (bool)$this->errors;
    }

    /**
     * @return string
     */
    public function getMessages(): string
    {

        return implode(",", $this->errors);
    }

    /**
     * @param AbstractService $service
     * @param array $params
     * @return mixed
     */
    abstract public function validateCreature(AbstractService $service, array $params);

    /**
     * @param AbstractService $service
     * @param array $params
     * @return mixed
     */
    abstract public function validateUpdate(AbstractService $service, array $params);
}
