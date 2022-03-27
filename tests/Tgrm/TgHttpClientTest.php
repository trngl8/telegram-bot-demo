<?php

namespace App\Test\Tgrm;

use App\Tgrm\TgrmHttpClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class TgHttpClientTest extends TestCase
{
    public function testGetMeSuccess(): void
    {
        $expected = [
            'ok' => true,
            'result' => [
                'id' => 1,
                'is_bot' => true,
                'first_name' => 'test',
                'username' => 'test_bot',
                'can_join_groups' => true,
                'can_read_all_group_messages' => false,
                'supports_inline_queries' => false
            ]

        ];

        $mockResponse = new MockResponse(json_encode($expected));
        $httpClient = new MockHttpClient($mockResponse, 'https://tg.com/');
        $client = new TgrmHttpClient($httpClient, 1, 'token');

        $result = $client->sendRequest('getMe', 'GET');
        $this->assertEquals(1, $result['id']);
    }
}
