<?php

namespace App\Services;

use App\Builder\BuilderInterface;
use App\Entity\EntityInterface;
use App\Services\Validation\AbstractValidation;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractService
{
    /**
     * AbstractService constructor.
     * @param BuilderInterface $builder
     * @param AbstractValidation $validator
     * @param ServiceEntityRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly BuilderInterface $builder,
        protected AbstractValidation $validator,
        protected ServiceEntityRepository $repository,
        protected EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param $id
     * @return null|object
     */
    public function find($id): ?object
    {
        try {
            $entity = $this->repository->find($id);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
            $entity = null;
        }

        return $entity;
    }

    /**
     * @param array $params
     * @return EntityInterface|null
     */
    public function create(array $params): ?EntityInterface
    {
        $this->validator->validateCreature($this, $params);
        return static::save($params);
    }

    /**
     * @param array $params
     * @return EntityInterface|null
     */
    protected function save(array $params): ?EntityInterface
    {
        $entity = $this->createEntity($params);
        $this->validator->entityValidation($entity);
        $this->entityManager->persist($entity);

        $this->entityManager->flush();

        return $entity;
    }


    /**
     * @param array $params
     * @return EntityInterface|null
     */
    public function update(array $params): ?EntityInterface
    {
        $this->validator->validateUpdate($this, $params);
        return static::save($params);
    }

    /**
     * @param array $params
     * @return EntityInterface|null
     */
    private function createEntity(array $params): ?EntityInterface
    {
        return $this->builder->build($params);
    }
}
