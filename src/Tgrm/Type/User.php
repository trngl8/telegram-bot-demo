<?php

namespace App\Tgrm\Type;

class User extends TgrmType
{
    public int $id;

    public bool $isBot;

    public string $firstName;

    public ?string $lastName;

    public ?string $username;

    public ?string $languageCode = null;

    public ?bool $canJoinGroups;

    public ?bool $canReadAllGroupMessages;

    public ?bool $supportsInlineQueries;
}
