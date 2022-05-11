<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class NewsDTO
{
    #[Assert\NotBlank]
    public string $title;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\NotBlank]
    public string $url;

    /**
     * @param \stdClass $args
     * @return NewsDTO
     */
    public static function load(\stdClass $args): NewsDTO
    {
        $dto = new self();

        $dto->title = $args->title ?? '';
        $dto->description = $args->description ?? '';
        $dto->url = $args->url ?? '';

        return $dto;
    }
}
