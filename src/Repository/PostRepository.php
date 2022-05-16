<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Dto\{Page, AbstractListDto, PostSummaryDto};
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Post;

/**
 * Class PostRepository
 * @package App\Repository
 */
class PostRepository extends ServiceEntityRepository implements RepositoryInterface
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
    public function search(string $q, int $offset = 0, int $limit = 20): AbstractListDto
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
}
