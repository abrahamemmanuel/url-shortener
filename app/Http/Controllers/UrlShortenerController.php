<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\UrlInputRequest;
use App\Interfaces\UrlShortenerInterface;

class UrlShortenerController extends Controller implements UrlShortenerInterface
{
    public function encode(UrlInputRequest $request): JsonResponse|Response
    {
        return response()->json([
            'message' => $request->input('url')
        ]);
    }

    public function decode(UrlInputRequest $request): JsonResponse|Response
    {
        //
    }

    public function redirect(UrlInputRequest $request): JsonResponse|Response
    {
        //
    }

    public function stats(Request $request): JsonResponse|Response
    {
        //
    }
}
