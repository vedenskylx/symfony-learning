<?php

namespace App\Controller;

use App\Annotation\Post;
use App\ArgumentResolver\QueryParam;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use App\Entity\User;
use App\Exception\AlreadyExistException;
use App\Exception\ValidationException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\{JsonResponse, Response};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: "/register", name: "register",)]
class RegistrationController extends AbstractController
{
    /** @var UserRepository */
    private UserRepository $userRepository;

    /** @var UserPasswordHasherInterface */
    private UserPasswordHasherInterface $hasher;

    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /** @var ValidatorInterface */
    private ValidatorInterface $validator;

    /** @var JWTTokenManagerInterface*/
    private JWTTokenManagerInterface $jwtManager;

    /**
     * @param UserRepository $repository
     * @param UserPasswordHasherInterface $hasher
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param JWTTokenManagerInterface $jwtManager
     */
    public function __construct(
        UserRepository $repository,
        UserPasswordHasherInterface $hasher,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->userRepository = $repository;
        $this->hasher = $hasher;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->jwtManager = $jwtManager;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws ExceptionInterface
     * @throws \Doctrine\ORM\ORMException
     */
    #[Post('', name: '')]
    public function index(
        #[QueryParam('email', true)] string $email,
        #[QueryParam('password', true)] string $password,
        #[QueryParam('password_repeat', true)] string $password_repeat
    ): JsonResponse {
        if ($user = $this->userRepository->findOneBy([
            'email' => $email
        ])) {
            throw new AlreadyExistException(User::class, $user->getEmail());
        } else {
            $user = new User($this->jwtManager);

            $user->setEmail($email);
            $user->setRoles(User::ROLE_USER);

            if ($password == $password_repeat) {
                $user->setPassword($this->hasher->hashPassword($user, $password));
                $errors = $this->validator->validate($user);

                if (count($errors) > 0) {
                    throw new ValidationException($errors);
                } else {
                    $this->userRepository->add($user);
                }
            } else {
                throw new ValidationException('Passwords must be equals!');
            }
        }

        return new JsonResponse(
            $this->serializer->normalize(
                $user,
                'array',
                ['groups' => ['public', 'registration']]
            ),
            Response::HTTP_CREATED,
            [],
            false
        );

    }
}
