<?php

namespace App\Controller;

use App\ArgumentResolver\QueryParam;
use App\Entity\Comment;
use App\Exception\CommentNotFoundException;
use App\Exception\PostNotFoundException;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Annotation\Get;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/posts", name: "posts_",)]
class PostController extends AbstractController
{
    /**
     * @param \App\Repository\PostRepository $posts
     * @param \App\Repository\CommentRepository $comments
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     */
    public function __construct(
        private readonly PostRepository $posts,
        private readonly CommentRepository $comments,
        private readonly EntityManagerInterface $manager
    ) {
    }

    #[Get (path: "", name: "all")]
    public function all(
        #[QueryParam] string $keyword,
        #[QueryParam] int $offset = 0,
        #[QueryParam] int $limit = 20
    ): JsonResponse {
        return new JsonResponse(
            $this->posts->findByKeyword($keyword ?: '', $offset, $limit),
            Response::HTTP_OK,
            [],
            false
        );
    }

    #[Route(path: "/{id}", name: "byId", methods: ["GET"])]
    function getById(string $id): Response
    {
        if (!($data = $this->posts->detailById($id))) {
            throw new PostNotFoundException($id);
        }

        return new JsonResponse(
            $data,
            Response::HTTP_OK,
            [],
            false
        );
    }

    #[Route(path: "/{id}/add-comment", name: "addComment", methods: ["POST"])]
    function addComment(
        string $id,
        #[QueryParam('comment', true)] string $comment
    ): JsonResponse {
        if (!($post = $this->posts->findOneBy(["id" => $id]))) {
            throw new PostNotFoundException($id);
        }

        $comment = Comment::of($comment)->setUser($this->getUser());
        $post->addComment($comment);
        $this->manager->persist($post);
        $this->manager->flush();

        return new JsonResponse(
            $comment,
            Response::HTTP_OK,
            [],
            false
        );
    }

    /**
     * @throws \Exception
     */
    #[Route(path: "/{id}/delete-comment/{comment_id}", name: "deleteComment", methods: ["POST"])]
    function deleteComment(
        string $id,
        string $comment_id
    ): JsonResponse {

        $user_id = $this->getUser()->getId();

        if (!($comment = $this->comments->findOneBy([
            'id'   => $comment_id,
            'post' => $id,
            'user' => $user_id
        ]))) {
            throw new CommentNotFoundException($comment_id, $user_id, $id);
        }
        $this->manager->remove($comment);
        $this->manager->flush();
        return new JsonResponse([], Response::HTTP_OK, [], false);
    }
}
