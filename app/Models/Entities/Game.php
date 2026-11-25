<?php
declare(strict_types=1);

namespace App\Models\Entities;

class Game
{
    public int $id;
    public int $author_id;
    public string $title;
    public ?string $short_description;
    public ?string $full_description;
    public ?string $engine;
    public ?string $genre;
    public ?string $release_date;
    public ?string $cover_url;

    public ?string $author_nickname = null;

    public static function fromArray(array $data): self
    {
        $game = new self();
        $game->id = (int) $data['id'];
        $game->author_id = (int) $data['author_id'];
        $game->title = $data['title'];
        $game->short_description = $data['short_description'] ?? null;
        $game->full_description = $data['full_description'] ?? null;
        $game->engine = $data['engine'] ?? null;
        $game->genre = $data['genre'] ?? null;
        $game->release_date = $data['release_date'] ?? null;
        $game->cover_url = $data['cover_url'] ?? null;
        return $game;
    }
}