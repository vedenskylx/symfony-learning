<?php

namespace App\Command;

use App\Dto\NewsDTO;
use App\Entity\User;
use App\Factory\PostFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use jcobhams\NewsApi\NewsApi;

#[AsCommand(
    name: 'app:bla',
    description: 'Bla',
)]
class BlaCommand extends Command
{
    /**
     * @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface
     */
    private ParameterBagInterface $params;

    /**
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        parent::__construct();
        $this->params = $params;
    }

    /**
     * @throws \jcobhams\NewsApi\NewsApiException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $country = 'ru';
        $newsapi = new NewsApi($this->params->get('app.news_api_key'));

        $top_headlines = $newsapi->getTopHeadlines(null, null, $country, null, 100, 1);

        if ($top_headlines->status ?? '' == 'ok') {
            foreach ($top_headlines->articles as $article) {
                $news = NewsDTO::load($article);
                var_dump($news->title);
            }
        }
    }
}
