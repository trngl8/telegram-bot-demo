<?php

namespace App\Tgrm\Method;

use App\Tgrm\Type\Info;

class SetWebhook  implements MethodInterface
{
    const COMMAND = 'setWebhook';
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
        return Info::class;
    }

}
