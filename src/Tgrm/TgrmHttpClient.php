<?php

namespace App\Tgrm;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;

class TgrmHttpClient implements ApiHttpClientInterface
{
    const BASE_URI_PATTERN = 'https://api.telegram.org/bot%d:%s/%s';

    private $client;

    private string $token;

    protected int $id;

    public function __construct(HttpClientInterface $client, int $tgBotId, string $tgBotToken)
    {
        $this->client = $client;
        $this->id = $tgBotId;
        $this->token = $tgBotToken;
    }

    public function sendRequest(string $command, string $method, ?array $params = []) : array
    {
        $options = $params ? ['body' => $params] : [];

        //TODO: check transport
        $response = $this->client->request($method, $this->getUri($command), $options);

        if(Response::HTTP_OK !== $response->getStatusCode()) {
            //TODO: throw custom exception
            throw new \RuntimeException(sprintf('Bad status %d. %s', $response->getStatusCode(), $response->getContent()));
        }

        $result = $response->toArray();

        if(true !== $result['ok']) {
            //TODO: throw custom exception
            throw new \RuntimeException(sprintf('Bad result: %s', $result['description']));
        }

        if(is_array($result['result'])) {
            return $result['result'];
        }

        return $result;
    }

    private function getUri(string $command)
    {
        return sprintf(self::BASE_URI_PATTERN,  $this->id, $this->token, $command);
    }
}
