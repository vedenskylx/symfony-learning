<?php

namespace App\Repository;

use App\Dto\Page;
use App\Dto\PostDetailDto;
use App\Dto\PostSummaryDto;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param string $q
     * @param int $offset
     * @param int $limit
     * @return Page
     */
    public function findByKeyword(string $q, int $offset = 0, int $limit = 20): Page
    {
        $query = $this->createQueryBuilder("p")
            ->andWhere("p.title like :q or p.content like :q")
            ->setParameter('q', "%" . $q . "%")
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $c = count($paginator);

        $content = new ArrayCollection();

        foreach ($paginator as $post) {
            $content->add(PostSummaryDto::of($post->getId(), $post->getTitle()));
        }

        return Page::of($content, $c, $offset, $limit);
    }

    /**
     * @param string $id
     * @return PostSummaryDto|null
     */
    public function findById(string $id): ?PostSummaryDto
    {
        $post = $this->findOneBy(["id" => $id]);

        if ($post) {
            $post = PostSummaryDto::of($post->getId(), $post->getTitle(), $post->getContent());
        }

        return $post;
    }

    /**
     * @param string $id
     * @return PostDetailDto|null
     */
    public function detailById(string $id): ?PostDetailDto
    {
        $post = $this->findOneBy(["id" => $id]);

        if ($post) {
            $post = PostDetailDto::of($post->getId(), $post->getTitle(), $post->getContent(), $post->getComments());
        }

        return $post;
    }
}
