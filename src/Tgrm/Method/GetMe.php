<?php

namespace App\Tgrm\Method;

use App\Tgrm\Type\User;

class GetMe implements MethodInterface
{
    const COMMAND = 'getMe';
    const METHOD = 'GET';

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
        return [];
    }

    public function getResultType(): string
    {
       return User::class;
    }
}
