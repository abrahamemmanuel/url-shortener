<?php
declare(strict_types=1);

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UrlInputRequest;

interface UrlShortenerInterface
{
    /**
     * Create a short url
     * @param string $longUrl
     */
    public function encode(UrlInputRequest $request): JsonResponse|Response;

    /**
     * Get a long url
     * @param string $shortUrl
     */
    public function decode(UrlInputRequest $request): JsonResponse|Response;

    /**
     * Redirect to a long url
     * @param string $shortUrl
     */
    public function redirect(UrlInputRequest $request, string $url_path): JsonResponse|RedirectResponse;

    /**
     * Get the stats of a short url
     * @param string $shortUrl
     */
    public function statistics(Request $request, string $url_path): JsonResponse|Response;
}