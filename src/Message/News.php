<?php

namespace App\Message;

class News
{
    /**
     * @param string $title
     * @param string $content
     * @param string $url
     */
    public function __construct(
        private readonly string $title,
        private readonly string $content,
        private readonly string $url,
    ) {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {

        return sprintf('%s %s', $this->content, $this->url);
    }
}
