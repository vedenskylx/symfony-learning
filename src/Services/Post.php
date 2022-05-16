<?php

namespace App\Services;

use App\Builder\Post as PostBuilder;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Exception\{CommentNotFoundException, PostNotFoundException};
use App\Repository\PostRepository;
use App\Services\Validation\PostValidation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Post
 * @package App\Services
 */
class Post extends AbstractService
{
    /**
     * Post constructor.
     *
     * @param PostRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param PostBuilder $builder
     * @param LoggerInterface $logger
     * @param PostValidation $validator
     */
    public function __construct(
        PostRepository $repository,
        EntityManagerInterface $entityManager,
        private readonly ManagerRegistry $registry,
        PostBuilder $builder,
        LoggerInterface $logger,
        PostValidation $validator
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($builder, $validator, $repository, $entityManager, $logger);
    }

    /**
     * @param string $id
     * @param string $comment
     * @param UserInterface $user
     * @return Comment
     */
    public function createComment(string $id, string $comment, UserInterface $user): Comment
    {
        if (!($post = $this->find($id))) {
            throw new PostNotFoundException($id);
        }

        $comment = Comment::of($comment)->setUser($user);
        $post->addComment($comment);
        $this->update();

        return $comment;
    }

    /**
     * @param string $id
     * @param string $comment_id
     * @param UserInterface $user
     * @return bool
     */
    public function deleteComment(string $id, string $comment_id, UserInterface $user): bool
    {
        if (!($post = $this->find($id))) {
            throw new PostNotFoundException($id);
        }

        $user_id = $user->getId();

        if (!($comment = (new CommentRepository($this->registry))->findOneBy([
            'id'   => $comment_id,
            'post' => $id,
            'user' => $user_id
        ]))) {
            throw new CommentNotFoundException($comment_id, $user_id, $id);
        }

        $post->removeComment($comment);

        return true;
    }
}
