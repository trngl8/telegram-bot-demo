<?php

namespace App\Tgrm\Method;

use App\Tgrm\Type\Message;

class SendMessage  implements MethodInterface
{
    const COMMAND = 'sendMessage';
    const METHOD = 'POST';

    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function getParams() : array
    {
        return $this->params;
    }

    public function getHttpMethod(): string
    {
        return self::METHOD;
    }

    public function getCommand(): string
    {
        return self::COMMAND;
    }

    public function getOptions(): ?array
    {
        return $this->params;
    }

    public function getResultType(): string
    {
        return Message::class;
    }

}
