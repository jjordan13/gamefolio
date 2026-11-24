<?php
declare(strict_types=1);

namespace App\Models\Entities;

class Tag
{
    public int $id;
    public string $name;
    public ?string $description;

    public static function fromArray(array $data): self
    {
        $tag = new self();
        $tag->id = (int) $data['id'];
        $tag->name = $data['name'];
        $tag->description = $data['description'] ?? null;
        return $tag;
    }
}