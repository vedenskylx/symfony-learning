<?php

namespace App\Builder;

use App\Entity\EntityInterface;
use App\Entity\User as UserEntity;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class User implements BuilderInterface
{
    /**
     * @param UserPasswordHasherInterface $hash
     * @param JWTTokenManagerInterface $jwtManager
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $hash,
        private readonly JWTTokenManagerInterface $jwtManager
    ) {
    }

    /**
     * @param array $params
     * @return EntityInterface
     * @throws \Exception
     */
    public function build(array $params): EntityInterface
    {
        return new UserEntity(
            $params['email'] ?? '',
            $params['password'] ?? '',
            $this->hash,
            $this->jwtManager
        );
    }
}
