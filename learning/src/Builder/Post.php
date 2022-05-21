<?php

namespace App\Builder;

use App\Entity\EntityInterface;
use App\Entity\Post as PostEntity;

/**
 * Class Post
 * @package App\Builder
 */
class Post implements BuilderInterface
{
    /**
     * @param array $params
     * @return EntityInterface
     * @throws \Exception
     */
    public function build(array $params): EntityInterface
    {
        return new PostEntity(
            $params['title'] ?? '',
            $params['content'] ?? ''
        );
    }
}
