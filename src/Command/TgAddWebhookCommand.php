<?php

namespace App\Command;

use App\Tgrm\Method\SetWebhook;
use App\Tgrm\TgrmService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand(
    name: 'app:tg-add-webhook',
    description: 'Add webhook based on routing',
)]
class TgAddWebhookCommand extends Command
{
    private $tgService;

    private $router;

    private string $token;

    protected int $id;

    private string $baseHost;

    public function __construct(string $name = null, TgrmService $tgrmService, RouterInterface $router, int $tgBotId, string $tgBotToken, string $baseHost)
    {
        parent::__construct($name);

        $this->tgService = $tgrmService;
        $this->router = $router;
        $this->id = $tgBotId;
        $this->token = $tgBotToken;
        $this->baseHost = $baseHost;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('route', InputArgument::OPTIONAL, 'Route name')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument('route')) {
            return;
        }

        $this->io->title('Add Webhook Interactive Wizard');

        $route = $input->getArgument('route');

        if (null !== $route) {
            $this->io->text(' > <info>Route</info>: '.$route);
        } else {
            $route = $this->io->ask('Route');
            $input->setArgument('route', $route);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $routeName = $input->getArgument('route');

        try {
            $url = 'https://'.$this->baseHost.$this->router->generate($routeName, ['id' => $this->id, 'token' => $this->token]);
        } catch (RouteNotFoundException $e) {
            $this->io->warning(sprintf('Route %s is not exists', $routeName));

            return Command::FAILURE;
        }

        $result = $this->tgService->call(new SetWebhook([
            'url' => $url
        ]));

        if(!$result->result) {
            $this->io->warning(sprintf('Error %d', $result->description));
        }

        $this->io->info(sprintf('Webhook %s is set', $url));

        $this->io->success('Success');

        return Command::SUCCESS;
    }
}
