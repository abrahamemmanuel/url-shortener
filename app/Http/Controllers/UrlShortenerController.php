<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UrlInputRequest;
use App\Interfaces\UrlShortenerInterface;
use App\Services\UrlShortenerService as UrlShortener;
use PUGX\Shortid\Shortid;
use Illuminate\Support\Facades\Cache;

class UrlShortenerController extends Controller implements UrlShortenerInterface
{
    public function encode(UrlInputRequest $request): JsonResponse|Response
    {
        return response()->json([
            'message' => 'Short URL created successfully',
            'success' => true,
            'data' => (new UrlShortener($request))->setShortLinkData()
        ], Response::HTTP_CREATED);
    }

    public function decode(UrlInputRequest $request): JsonResponse|Response
    {

    }

    public function redirect(Request $request, $url_path): JsonResponse|RedirectResponse
    {

    }

    public function stats(Request $request, $url_path): JsonResponse|Response
    {

    }
}
