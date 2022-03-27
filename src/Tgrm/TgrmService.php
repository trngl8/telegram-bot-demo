<?php

namespace App\Tgrm;

use App\Tgrm\Method\MethodInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TgrmService
{
    private $tg;

    private $logger;

    public function __construct(ApiHttpClientInterface $tgHttpClient, LoggerInterface $logger)
    {
        $this->tg = $tgHttpClient;
        $this->logger = $logger;

        $this->serializer = new Serializer([
            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())
        ], [
            new JsonEncoder()
        ]);
    }

    public function call(MethodInterface $method)
    {
        try {
            $result = $this->tg->sendRequest($method->getCommand(), $method->getHttpMethod(), $method->getOptions());
        } catch (\Exception $exception) {
            $this->logger->warning($exception->getMessage(), $method->getOptions());
            throw $exception;
        }

        //TODO: check result type
        return $this->serializer->deserialize(json_encode($result), $method->getResultType(), 'json');
    }
}
