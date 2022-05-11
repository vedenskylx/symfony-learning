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
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    private MessageBusInterface $bus;

    /**
     * @param \Symfony\Component\Messenger\MessageBusInterface $bus
     */
    public function __construct(
        MessageBusInterface $bus
    ) {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('city', InputArgument::REQUIRED, 'Title of city');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new Weather($input->getArgument('city')));

        return Command::SUCCESS;
    }
}
