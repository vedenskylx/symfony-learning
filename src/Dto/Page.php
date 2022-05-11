<?php

namespace App\Dto;

use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;

class Page
{
    public array $content;
    public int $totalElements;
    public int $offset;
    public int $limit;

    #[Pure] public function __construct()
    {
        $this->content = [];
    }


    public static function of(Collection $content, int $totalElements, int $offset = 0, int $limit = 20): Page
    {
        ($page = new Page())
            ->setContent($content)
            ->setTotalElements($totalElements)
            ->setOffset($offset)
            ->setLimit($limit);

        return $page;
    }

    /**
     * @param Collection $content
     * @return Page
     */
    public function setContent(Collection $content): Page
    {
        $this->content = $content->getValues();
        return $this;
    }

    /**
     * @param int $totalElements
     * @return Page
     */
    public function setTotalElements(int $totalElements): Page
    {
        $this->totalElements = $totalElements;
        return $this;
    }

    /**
     * @param int $offset
     * @return Page
     */
    public function setOffset(int $offset): Page
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param int $limit
     * @return Page
     */
    public function setLimit(int $limit): Page
    {
        $this->limit = $limit;
        return $this;
    }


    /**
     * @return Collection
     */
    public function getContent(): Collection
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
