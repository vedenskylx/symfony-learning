<?php

namespace App\Dto;

/**
 * Class AbstractDetailDto
 * @package App\Dto
 */
abstract class AbstractDetailDto
{
    /**
     * AbstractListDto constructor.
     *
     * @param string $id
     */
    public function __construct(
        public string $id
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
