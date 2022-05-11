<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class PostDetailDto
{
    /** @var string */
    public string $id;
    /** @var string */
    public string $title;
    /** @var string */
    public string $content;
    /** @var array */
    public array $comments;

    /**
     * @param string $id
     * @param string $title
     * @param string $content
     * @param Collection|null $comments
     * @return PostDetailDto
     */
    public static function of(
        string $id,
        string $title,
        string $content = '',
        Collection $comments = null
    ): PostDetailDto {
        $dto = new PostDetailDto();
        $dto->setId($id)
            ->setTitle($title);

        if (!empty($content)) {
            $dto->setContent($content);
        }

        if (!empty($comments)) {
            $commentsCollection = new ArrayCollection();

            foreach ($comments as $comment) {
                $commentsCollection->add(CommentDto::of(
                    $comment->getId(),
                    $comment->getContent(),
                    $comment->getUser()
                ));
            }

            if (!empty($commentsCollection)) {
                $dto->setComments($commentsCollection);
            }
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

    /**
     * @param Collection $arrayCollection
     * @return $this
     */
    public function setComments(Collection $arrayCollection): self
    {
        $this->comments = $arrayCollection->getValues();
        return $this;
    }

}
