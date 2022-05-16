<?php

namespace App\Controller;

use App\ArgumentResolver\QueryParam;
use App\Annotation\Get;
use App\Services\Post as PostService;
use App\Builder\Post as PostBuilder;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/posts", name: "posts_",)]
class PostController extends AbstractController
{
    /**
     * PostController constructor.
     *
     * @param PostService $service
     * @param PostBuilder $builder
     */
    public function __construct(
        PostService $service,
        PostBuilder $builder
    ) {
        parent::__construct($service, $builder);
    }

    #[Get (path: "", name: "all")]
    public function all(
        #[QueryParam] string $keyword,
        #[QueryParam] int $offset = 0,
        #[QueryParam] int $limit = 20
    ): Response {
        return parent::getSearchAction($keyword ?: '', $offset, $limit);
    }

    #[Route(path: "/{id}", name: "detail", methods: ["GET"])]
    function detail(string $id): Response
    {
        return parent::getAction($id, ['detail']);
    }

    #[Route(path: "/{id}/add-comment", name: "addComment", methods: ["POST"])]
    function addComment(
        string $id,
        #[QueryParam('comment', true)] string $comment
    ): Response {

        return $this->handleView(new View(
            $this->service->createComment($id, $comment, $this->getUser()),
            Response::HTTP_OK
        ), ['detail']);
    }

    /**
     * @throws \Exception
     */
    #[Route(path: "/{id}/delete-comment/{comment_id}", name: "deleteComment", methods: ["DELETE"])]
    function deleteComment(
        string $id,
        string $comment_id
    ): Response {

        return $this->handleView(new View(
            $this->service->deleteComment($id, $comment_id, $this->getUser()),
            Response::HTTP_OK
        ), ['detail']);
    }
}
