<?php

namespace App\Dto;

use Doctrine\Common\Collections\Collection;

/**
 * Class AbstractListDto
 * @package App\Dto
 */
abstract class AbstractListDto
{
    /**
     * @var array
     */
    public array $content;

    /**
     * AbstractListDto constructor.
     *
     * @param Collection $content
     * @param int $totalElements
     * @param int $offset
     * @param int $limit
     */
    public function __construct(
        Collection $content,
        public int $totalElements,
        public int $offset,
        public int $limit
    ) {
        $this->setContent($content);
    }

    /**
     * @param Collection $content
     * @return AbstractListDto
     */
    public function setContent(Collection $content): AbstractListDto
    {
        $this->content = $content->getValues();
        return $this;
    }

    /**
     * @param int $totalElements
     * @return AbstractListDto
     */
    public function setTotalElements(int $totalElements): AbstractListDto
    {
        $this->totalElements = $totalElements;
        return $this;
    }

    /**
     * @param int $offset
     * @return Page
     */
    public function setOffset(int $offset): AbstractListDto
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param int $limit
     * @return Page
     */
    public function setLimit(int $limit): AbstractListDto
    {
        $this->limit = $limit;
        return $this;
    }


    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getTotalElements(): int
    {
        return $this->totalElements;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
