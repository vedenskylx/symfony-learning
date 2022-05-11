<?php

namespace App\Exception;

class CommentNotFoundException extends \RuntimeException
{
    const POST_NOT_FOUND_ERROR_TEMPLATE = "Comment #%s of user #%s in post #%s not found";

    public function __construct(string $uuid, string $user_id, string $post_id)
    {
        parent::__construct(sprintf(self::POST_NOT_FOUND_ERROR_TEMPLATE, $uuid, $user_id, $post_id), 404);
    }
}
