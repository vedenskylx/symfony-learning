<?php

namespace App\Dto;

class UserPublicDto
{
    /** @var string */
    public string $id;

    /** @var string */
    public string $email;

    /**
     * @param string $id
     * @param string $email
     * @return UserPublicDto
     */
    static function of(string $id, string $email): UserPublicDto
    {
        $dto = new UserPublicDto();
        $dto->setId($id)
            ->setEmail($email);

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
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
}
