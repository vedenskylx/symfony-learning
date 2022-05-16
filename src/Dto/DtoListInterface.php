<?php

namespace App\Dto;

use Doctrine\Common\Collections\Collection;

/**
 * Class DtoListInterface
 * @package App\Dto
 */
interface DtoListInterface
{
    /**
     * @param Collection $content
     * @param int $totalElements
     * @param int $offset
     * @param int $limit
     * @return AbstractListDto
     */
    public static function of(
        Collection $content,
        int $totalElements,
        int $offset = 0,
        int $limit = 20
    ): AbstractListDto;
}
