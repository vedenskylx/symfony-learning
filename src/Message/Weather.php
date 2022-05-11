<?php

namespace App\Message;

class Weather
{
    /**
     * @var string
     */
    private string $city;

    /**
     * @param string $city
     */
    public function __construct(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }
}
