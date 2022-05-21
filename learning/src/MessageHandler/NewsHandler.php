<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Services\Post as PostService;
use App\Builder\Post as PostBuilder;
use Psr\Log\LoggerInterface;
use App\Message\News;

/**
 * Class NewsHandler
 * @package App\MessageHandler
 */
class NewsHandler extends AbstractMessageHandler implements MessageHandlerInterface
{
    /**
     * NewsHandler constructor.
     *
     * @param PostService $service
     * @param PostBuilder $builder
     * @param LoggerInterface $logger
     */
    public function __construct(
        PostService $service,
        PostBuilder $builder,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($service, $builder);
    }

    /**
     * @param News $message
     * @return void
     */
    public function __invoke(News $message): void
    {
        try {
            $this->service->create([
                'title'   => $message->getTitle(),
                'content' => $message->getContent()
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
}
