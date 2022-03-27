<?php

namespace App\Tgrm\Method;

interface MethodInterface
{
    public function getCommand() : string;

    public function getHttpMethod() : string;

    public function getOptions() : ?array;

    public function getResultType() : string;
}
