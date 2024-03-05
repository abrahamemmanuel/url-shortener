<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\ShortUrl;

class UrlShortenerRepository
{
    public static function saveUrlPath(string $long_url, string $short_url): bool
    {
        $shortLink = new ShortUrl();
        $shortLink->long_url = $long_url;
        $shortLink->short_url = $short_url;
        $shortLink->url_path = explode('/', $short_url)[3];
        if ($shortLink->save()) {
            return true;
        }else {
            return false;
        }
    }
}