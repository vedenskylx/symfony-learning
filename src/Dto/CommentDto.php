<?php

namespace App\Dto;

use App\Entity\User;

/**
 * Class CommentDto
 * @package App\Dto
 */
class CommentDto extends AbstractDetailDto implements DtoDetailInterface
{
    /**
     * CommentDto constructor.
     *
     * @param string $id
     * @param string $content
     * @param UserPublicDto|null $user
     */
    public function __construct(
        string $id,
        public string $content,
        public ?UserPublicDto $user = null
    ) {
        parent::__construct($id);
    }

    /**
     * @param string $id
     * @param string $content
     * @param User|null $user
     * @return CommentDto
     */
    static function of(string $id, string $content = '', User $user = null): CommentDto
    {
        $dto = new CommentDto($id, $content);

        if (!empty($user)) {
            $dto->setUser(UserPublicDto::of($user->getId(), $user->getEmail()));
        }

        return $dto;
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
