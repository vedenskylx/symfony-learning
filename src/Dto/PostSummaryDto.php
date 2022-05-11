<?php

namespace App\Dto;

class PostSummaryDto
{
    /** @var string */
    public string $id;
    /** @var string */
    public string $title;
    /** @var string */
    public string $content;

    /**
     * @param string $id
     * @param string $title
     * @param string $content
     * @return PostSummaryDto
     */
    static function of(string $id, string $title, string $content = ''): PostSummaryDto
    {
        $dto = new PostSummaryDto();
        $dto
            ->setId($id)
            ->setTitle($title);

        if (!empty($content)) {
            $dto->setContent($content);
        }

        return $dto;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

}
