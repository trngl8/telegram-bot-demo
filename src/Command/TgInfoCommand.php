<?php

namespace App\Command;

use App\Tgrm\Method\GetMe;
use App\Tgrm\TgrmService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:tg-info',
    description: 'Add a short description for your command',
)]
class TgInfoCommand extends Command
{
    private $tgService;

    public function __construct(string $name = null, TgrmService $tgrm)
    {
        parent::__construct($name);

        $this->tgService = $tgrm;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->tgService->call(new GetMe());

        $io->success( sprintf('User #%d (%s)',  $result->id,  $result->username));

        $io->definitionList(
            ['ID' => $result->id],
            ['First Name' => $result->firstName],
            ['Is Bot' => $result->isBot],
            ['Language' => $result->languageCode],
        );

        $io->success('Success');

        return Command::SUCCESS;
    }
}
