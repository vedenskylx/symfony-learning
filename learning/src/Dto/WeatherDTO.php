<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class WeatherDTO
 * @package App\Dto
 */
class WeatherDTO
{
    const POST_TITLE_TEMPLATE = 'Погода в городе %s, %s';
    const POST_CONTENT_TEMPLATE = 'Температура в городе %s: %s˚C, ощущается как %s˚C. Скорость ветра: %sм/с.';

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $wind;

    #[Assert\NotBlank]
    public string $temp;

    #[Assert\NotBlank]
    public string $feels_like;

    /**
     * @param array $args
     * @return WeatherDTO
     */
    public static function load(array $args): WeatherDTO
    {
        $dto = new self();

        $dto->name = $args['name'] ?? '';
        $dto->wind = $args['wind']['speed'] ?? '';
        $dto->temp = $args['main']['temp'] ?? '';
        $dto->feels_like = $args['main']['feels_like'] ?? '';

        return $dto;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return sprintf(self::POST_TITLE_TEMPLATE, $this->getName(), date("m.d.y, g:i a"));
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return sprintf(self::POST_CONTENT_TEMPLATE, $this->getName(), $this->temp, $this->feels_like, $this->wind);
    }

    /**
     * @return string
     */
    private function getName(): string
    {
        return preg_replace('%[^a-zа-я\d]%i', '', $this->name);
    }
}
