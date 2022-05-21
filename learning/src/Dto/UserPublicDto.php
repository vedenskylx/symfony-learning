<?php

namespace App\Dto;

/**
 * Class UserPublicDto
 * @package App\Dto
 */
class UserPublicDto extends AbstractDetailDto implements DtoDetailInterface
{
    /**
     * UserPublicDto constructor.
     *
     * @param string $id
     * @param string $email
     */
    public function __construct(
        string $id,
        public string $email
    ) {
        parent::__construct($id);
    }

    /**
     * @param string $id
     * @param string $email
     * @return UserPublicDto
     */
    static function of(string $id, string $email): AbstractDetailDto
    {
        return new UserPublicDto($id, $email);
    }
}
