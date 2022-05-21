<?php

namespace App\Dto;

use Doctrine\Common\Collections\Collection;

/**
 * Class Page
 * @package App\Dto
 */
class Page extends AbstractListDto implements DtoListInterface
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
    ): AbstractListDto {
        return new Page($content, $totalElements, $offset, $limit);
    }
}
