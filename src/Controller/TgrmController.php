<?php

namespace App\Controller;

use App\Tgrm\Method\SendMessage;
use App\Tgrm\TgrmService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TgrmController extends AbstractController
{
    private $tgrmService;

    public function __construct(TgrmService $tgrmService)
    {
        $this->tgrmService = $tgrmService;
    }

    #[Route('/tgrm/bot{id}:{token}', name: 'app_tgrm', methods: 'POST')]
    public function index(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if(isset($data['message'])) {
            $chatId = $data['message']['chat']['id'];

            if('/start' === $data['message']['text']) {
                $this->tgrmService->call(new SendMessage([
                    'chat_id' => $chatId,
                    'text' => 'test'
                ]));
            }

            $this->tgrmService->call(new SendMessage([
                'chat_id' => $chatId,
                'text' => 'test'
            ]));
        }

        return $this->json($data);
    }
}
