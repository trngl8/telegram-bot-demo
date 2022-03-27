<?php

namespace App\Test\Tgrm;

use App\Tgrm\Method\GetMe;
use App\Tgrm\Method\SendMessage;
use App\Tgrm\Method\SetWebhook;
use App\Tgrm\TgrmHttpClient;
use App\Tgrm\TgrmService;
use App\Tgrm\Type\Info;
use App\Tgrm\Type\Message;
use App\Tgrm\Type\User;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class TgrmServiceTest extends TestCase
{
    private $tgrmHttClient;

    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->tgrmHttClient = $this->createMock(TgrmHttpClient::class);
        $this->service = new TgrmService($this->tgrmHttClient, new NullLogger());
    }

    public function testGetMeSuccess(): void
    {
        $operation = new GetMe();

        $this->tgrmHttClient->method('sendRequest')
            ->willReturn(['id' => 1]);

        $result = $this->service->call($operation);
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals(1, $result->id);
    }

    public function testSetWebhookSuccess(): void
    {
        $operation = new SetWebhook([
            'url' => 'https://test.com/webhook'
        ]);

        $this->tgrmHttClient->method('sendRequest')
            ->willReturn(['result' => true, 'description' => 'Webhook was set']);

        $result = $this->service->call($operation);
        $this->assertInstanceOf(Info::class, $result);
        $this->assertTrue($result->result);
    }

    public function testSetSendMessageSuccess(): void
    {
        $operation = new SendMessage([
            'chat_id' => 1,
            'text' => 'test'
        ]);

        $this->tgrmHttClient->method('sendRequest')
            ->willReturn([]);

        $result = $this->service->call($operation);
        $this->assertInstanceOf(Message::class, $result);
    }
}
