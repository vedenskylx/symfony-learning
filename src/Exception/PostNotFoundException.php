<?php

namespace App\Exception;

class PostNotFoundException extends \RuntimeException
{
    const POST_NOT_FOUND_ERROR_TEMPLATE = "Post #%s was not found";

    public function __construct(string $uuid)
    {
        parent::__construct(sprintf(self::POST_NOT_FOUND_ERROR_TEMPLATE, $uuid), 404);
    }
}
