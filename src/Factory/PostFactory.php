<?php

namespace App\Factory;

use App\Entity\Post;

/**
 * Class PostFactory
 * @package App\Factory
 */
class PostFactory
{
    /**
     * @param string $title
     * @param string $content
     * @return \App\Entity\Post
     */
    public static function create(string $title, string $content): Post
    {
        return new Post($title, $content);
    }
}
