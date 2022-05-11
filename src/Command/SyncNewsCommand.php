<?php

namespace App\Command;

use App\Dto\NewsDTO;
use App\Message\News;
use App\Message\Weather;
use jcobhams\NewsApi\NewsApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:sync-news',
    description: 'Add a short description for your command',
)]
class SyncNewsCommand extends Command
{
    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    private MessageBusInterface $bus;
    /**
     * @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface
     */
    private ParameterBagInterface $params;

    /**
     * @param \Symfony\Component\Messenger\MessageBusInterface $bus
     */
    public function __construct(
        MessageBusInterface $bus,
        ParameterBagInterface $params
    ) {
        parent::__construct();
        $this->bus = $bus;
        $this->params = $params;
    }

    /**
     * @throws \jcobhams\NewsApi\NewsApiException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $country = 'ru';
        $newsapi = new NewsApi($this->params->get('app.news_api_key'));

        $top_headlines = $newsapi->getTopHeadlines(null, null, $country, null, 100, 1);

        if ($top_headlines->status ?? '' == 'ok') {
            foreach ($top_headlines->articles as $article) {
                $news = NewsDTO::load($article);
                $this->bus->dispatch(new News($news->title, $news->description, $news->url));
            }
        }

        return Command::SUCCESS;
    }
}
