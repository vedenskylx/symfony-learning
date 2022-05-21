<?php

namespace App\MessageHandler;

use App\Dto\WeatherDTO;
use App\Message\Weather;
use Psr\Log\LoggerInterface;
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
use App\Services\Post as PostService;
use App\Builder\Post as PostBuilder;

/**
 * Class WeatherHandler
 * @package App\MessageHandler
 */
class WeatherHandler extends AbstractMessageHandler implements MessageHandlerInterface
{
    /**
     * WeatherHandler constructor.
     * @param PostService $service
     * @param PostBuilder $builder
     * @param HttpClientInterface $client
     * @param ParameterBagInterface $params
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        PostService $service,
        PostBuilder $builder,
        private readonly HttpClientInterface $client,
        private readonly ParameterBagInterface $params,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($service, $builder);
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
    ): void {
        try {
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

                $dto = WeatherDTO::load($response->toArray());

                $this->service->create([
                    'title'   => $dto->getTitle(),
                    'content' => $dto->getContent()
                ]);

            } else {
                throw new \Exception(sprintf('Request error, status: %s', $response->getStatusCode()));
            }
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
}
