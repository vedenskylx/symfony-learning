<?php

namespace App\Command;

use App\Dto\NewsDTO;
use App\Message\News;
use jcobhams\NewsApi\NewsApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use jcobhams\NewsApi\NewsApiException;

#[AsCommand(
    name: 'app:sync-news',
    description: 'Add a short description for your command',
)]
class SyncNewsCommand extends Command
{
    /**
     * @param MessageBusInterface $bus
     * @param ParameterBagInterface $params
     */
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly ParameterBagInterface $params
    ) {
        parent::__construct();
    }

    /**
     * @throws NewsApiException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $country = 'ru';
        $service = new NewsApi($this->params->get('app.news_api_key'));

        $top_headlines = $service->getTopHeadlines(null, null, $country, null, 100, 1);

        if ($top_headlines->status ?? '' == 'ok') {
            foreach ($top_headlines->articles as $article) {
                $news = NewsDTO::load($article);
                $this->bus->dispatch(new News($news->title, $news->description, $news->url));
            }
        }

        return Command::SUCCESS;
    }
}
