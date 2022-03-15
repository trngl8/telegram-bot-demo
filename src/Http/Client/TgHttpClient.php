<?php

namespace App\Http\Client;

use App\Model\DTO\User;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;

class TgHttpClient
{
    const BASE_URI_PATTERN = 'https://api.telegram.org/bot%s/%s';

    private $client;

    private string $token;

    public function __construct(HttpClientInterface $client, string $tgBotToken)
    {
        $this->client = $client;
        $this->token = $tgBotToken;
    }

    public function getMe()
    {
        $result = json_encode($this->call('getMe'));

        $serializer = new Serializer([
            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())
        ], [
            new JsonEncoder()
        ]);
        return $serializer->deserialize($result, User::class, 'json');
    }

    public function call(string $command) : array
    {
        //TODO: check transport
        $response = $this->client->request('GET', sprintf(self::BASE_URI_PATTERN, $this->token, $command));

        if(Response::HTTP_OK !== $response->getStatusCode()) {
            throw new \RuntimeException(sprintf('Bad status %d. %s', $response->getStatusCode(), $response->getContent()));
        }

        $result = $response->toArray();

        if(true !== $result['ok']) {
            throw new \RuntimeException(sprintf('Bad result: %s', $result['description']));
        }

        return $result['result'];
    }
}
