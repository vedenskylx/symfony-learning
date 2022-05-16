<?php

namespace App\Dto;

use Doctrine\Common\Collections\{ArrayCollection, Collection};

/**
 * Class PostDetailDto
 * @package App\Dto
 */
class PostDetailDto extends AbstractDetailDto implements DtoDetailInterface
{
    /**
     * PostDetailDto constructor.
     *
     * @param string $id
     * @param string $title
     * @param string $content
     * @param array $comments
     */
    public function __construct(
        string $id,
        public string $title,
        public string $content,
        public array $comments = []
    ) {
        parent::__construct($id);
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $content
     * @param Collection|null $comments
     * @return AbstractDetailDto
     */
    public static function of(
        string $id,
        string $title,
        string $content = '',
        Collection $comments = null
    ): AbstractDetailDto {

        $dto = new PostDetailDto($id, $title, $content);

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
     * @param Collection $arrayCollection
     * @return $this
     */
    public function setComments(Collection $arrayCollection): self
    {
        $this->comments = $arrayCollection->getValues();
        return $this;
    }

}
