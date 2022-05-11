<?php

namespace App\MessageHandler;

use App\Dto\WeatherDTO;
use App\Entity\Tag;
use App\Factory\PostFactory;
use App\Message\News;
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


class NewsHandler implements MessageHandlerInterface
{
    const TAG = 'News';

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     */
    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {
    }

    /**
     * @param \App\Message\News $message
     * @return void
     */
    public function __invoke(
        News $message,
    ): void {
        $entity = PostFactory::create($message->getTitle(), $message->getContent());
        $entity->addTag(Tag::of(self::TAG));
        $this->manager->persist($entity);
        $this->manager->flush();
    }
}
