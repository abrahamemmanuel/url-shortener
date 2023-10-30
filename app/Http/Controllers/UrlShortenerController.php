<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Interfaces\UrlShortenerInterface;

class UrlShortenerController extends Controller implements UrlShortenerInterface
{
    /**
     * Create a short url
     *
     * @param string $longUrl
     * @return string
     */
    public function encode(string $longUrl): string
    {
        //
    }

    /**
     * Get a long url
     *
     * @param string $shortUrl
     * @return string
     */
    public function decode(string $shortUrl): string
    {
        //
    }

    /**
     * Redirect to a long url
     *
     * @param string $shortUrl
     * @return string
     */
    public function redirect(string $shortUrl): string
    {
        //
    }

    /**
     * Get the stats of a short url
     *
     * @param string $shortUrl
     * @return array
     */
    public function stats(string $shortUrl): array
    {
        //
    }
    //
}
