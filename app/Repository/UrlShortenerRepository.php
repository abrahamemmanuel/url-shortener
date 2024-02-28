<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\ShortUrl;

class UrlShortenerRepository
{
    public static function saveUrlPath(object $data): void
    {
        dd($data);
        ShortUrl::create($data);
    }
}