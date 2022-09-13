<?php

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class BlogDTO extends DataTransferObject
{
    public ?int $id;

    public ?string $content;

    public ?int $account_id;

    public ?string $title;

    public ?\DateTime $create_at;

    public ?array $categories;
}
