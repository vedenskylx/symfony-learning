<?php

namespace App\MessageHandler;

use App\Dto\WeatherDTO;
use App\Entity\Tag;
use App\Factory\PostFactory;
use App\Message\Weather;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use \Symfony\Contracts\HttpClient\Exception\{
    ClientExceptionInterface,
    DecodingExceptionInterface,
    RedirectionExceptionInterface,
    ServerExceptionInterface,
    TransportExceptionInterface
};


class WeatherHandler implements MessageHandlerInterface
{
    const POST_TITLE_TEMPLATE = 'Погода в городе %s, %s';
    const TAG = 'Weather';

    /**
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $client
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $params
     */
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $manager,
        private readonly ParameterBagInterface $params
    ) {
    }

    /**
     * @param Weather $message
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function __invoke(
        Weather $message,
    ) {
        $response = $this->client->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather',
            [
                'query' => [
                    'q'     => $message->getCity(),
                    'appid' => $this->params->get('app.weather_api_key'),
                    'units' => 'metric'
                ]
            ]
        );

        if ($response->getStatusCode() == Response::HTTP_OK) {
            $content = $response->toArray();
            $weather = WeatherDTO::load($content);
            $post = PostFactory::create(sprintf(self::POST_TITLE_TEMPLATE, $weather->getName(), date("m.d.y, g:i a")), $weather->__toString());
            $post->addTag(Tag::of(self::TAG));
            $entity = $post;
            $this->manager->persist($entity);
            $this->manager->flush();
        } else {
            throw new \Exception(sprintf('Request error, status: %s', $response->getStatusCode()));
        }
    }
}
