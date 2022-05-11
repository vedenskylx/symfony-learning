<?php

namespace App\Dto;

use App\Entity\User;

class CommentDto
{
    /** @var string */
    public string $id;

    /** @var string */
    public string $content;

    public UserPublicDto $user;

    /**
     * @param string $id
     * @param string $content
     * @param User|null $user
     * @return CommentDto
     */
    static function of(string $id, string $content = '', User $user = null): CommentDto
    {
        $dto = new CommentDto();
        $dto->setId($id)
            ->setContent($content);

        if (!empty($user)) {
            $dto->setUser(UserPublicDto::of($user->getId(), $user->getEmail()));
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
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param UserPublicDto $user
     * @return $this
     */
    public function setUser(UserPublicDto $user): self
    {
        $this->user = $user;
        return $this;
    }
}
