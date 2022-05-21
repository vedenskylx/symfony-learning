<?php

namespace App\Command;

use App\Message\Weather;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:sync-weather',
    description: 'Add a short description for your command',
)]
class SyncWeatherCommand extends Command
{
    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(
        private readonly MessageBusInterface $bus
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('city', InputArgument::REQUIRED, 'Title of city');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new Weather($input->getArgument('city')));

        return Command::SUCCESS;
    }
}
