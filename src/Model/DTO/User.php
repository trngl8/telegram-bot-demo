<?php

namespace App\Model\DTO;

class User
{
    public int $id;

    public bool $isBot;

    public string $firstName;

    public ?string $lastName;

    public ?string $username;

    public ?string $languageCode;

    public ?bool $canJoinGroups;

    public ?bool $canReadAllGroupMessages;

    public ?bool $supportInlineQueries;
}
