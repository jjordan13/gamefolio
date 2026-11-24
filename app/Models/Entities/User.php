<?php
declare(strict_types=1);

namespace App\Models\Entities;

class User
{
    public int $id;
    public string $login;
    public string $password_hash;
    public string $nickname;
    public ?string $bio;
    public ?string $avatar_url;
    public ?string $banner_url;
    public string $theme;
    public string $lang;

    // Метод для создания объекта из массива (строки БД)
    public static function fromArray(array $data): self
    {
        $user = new self();
        $user->id = (int) $data['id'];
        $user->login = $data['login'];
        $user->password_hash = $data['password_hash'];
        $user->nickname = $data['nickname'];
        $user->bio = $data['bio'] ?? null;
        $user->avatar_url = $data['avatar_url'] ?? null;
        $user->banner_url = $data['banner_url'] ?? null;
        $user->theme = $data['theme'] ?? 'dark';
        $user->lang = $data['lang'] ?? 'ru';
        return $user;
    }
}