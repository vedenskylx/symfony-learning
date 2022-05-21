<?php

namespace App\Controller;

use App\Annotation\Post;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Response, Request};
use App\Services\User as UserService;
use App\Builder\User as UserBuilder;

#[Route(path: "/user", name: "user")]
class UserController extends AbstractController
{
    /**
     * UserController constructor.
     *
     * @param UserService $service
     * @param UserBuilder $builder
     */
    public function __construct(
        UserService $service,
        UserBuilder $builder
    ) {
        parent::__construct($service, $builder);
    }

    #[Post('/register', name: 'register')]
    public function registerAction(Request $request): Response
    {
        return parent::createAction($request, ['public', 'registration']);
    }
}
