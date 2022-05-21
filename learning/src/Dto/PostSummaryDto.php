<?php

namespace App\Dto;

/**
 * Class PostSummaryDto
 * @package App\Dto
 */
class PostSummaryDto extends AbstractDetailDto implements DtoDetailInterface
{
    /**
     * PostSummaryDto constructor.
     *
     * @param string $id
     * @param string $title
     */
    public function __construct(
        string $id,
        public string $title
    ) {
        parent::__construct($id);
    }

    /**
     * @param string $id
     * @param string $title
     * @return PostSummaryDto
     */
    static function of(string $id, string $title): AbstractDetailDto
    {
        return new PostSummaryDto($id, $title);
    }
}
