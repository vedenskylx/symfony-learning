<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class WeatherDTO
{
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
    public function getName(): string
    {
        return  preg_replace('%[^a-zа-я\d]%i', '', $this->name);
    }

    public function __toString(): string
    {
        $template = 'Температура в городе %s: %s˚C, ощущается как %s˚C. Скорость ветра: %sм/с.';
        return sprintf($template, $this->getName(), $this->temp, $this->feels_like, $this->wind);
    }
}
