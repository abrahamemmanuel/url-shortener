<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UrlInputRequest;
use App\Interfaces\UrlShortenerInterface;
use PUGX\Shortid\Shortid;
use Illuminate\Support\Facades\Cache;

class UrlShortenerController extends Controller implements UrlShortenerInterface
{
    public function encode(UrlInputRequest $request): JsonResponse|Response
    {
        $id = Shortid::generate();
        $url = $request->input('url');
        $base_url = $request->root();
        $short_url = $base_url . '/' . $id;
        $data = [
            'long_url' => $url,
            'short_url' => $short_url,
            'url_path' => $id,
            'stats' => [
                'click_count' => 0,
                'last_accessed' => null,
                'date_time_accessed' => [],
                'referrer' => [],
                'geographic' => [
                    'country' => [],
                    'city' => []
                ],
                'device_info' => []
            ],
            'created_at' => now(),
            'updated_at' => now()
        ];

        Cache::put($id, $data, 60 * 60);

        return response()->json([
            'message' => $url . ' successfully shortened',
            'success' => true,
            'data' => $data['short_url'],
        ], Response::HTTP_OK);
    }

    public function decode(UrlInputRequest $request): JsonResponse|Response
    {
        $short_url = $request->input('url');
        $url_path = explode('/', $short_url)[3];
        $data = Cache::get($url_path);
        return response()->json([
            'message' => $short_url . ' successfully decoded',
            'success' => true,
            'data' => $data['long_url'],
        ], Response::HTTP_OK);
    }

    public function redirect(Request $request, $url_path): JsonResponse|RedirectResponse
    {
        $data = Cache::get($url_path);
        $data['stats']['click_count'] += 1;
        $data['stats']['last_accessed'] = now();
        $data['stats']['date_time_accessed'][] = now();
        $data['stats']['referrer'][] = $request->server('HTTP_REFERER');
        $data['stats']['geographic']['country'][] = $request->server('HTTP_CF_IPCOUNTRY');
        $data['stats']['geographic']['city'][] = $request->server('HTTP_CF_IPCITY');
        $data['stats']['device_info'][] = $request->server('HTTP_USER_AGENT') . $_SERVER['REMOTE_ADDR'];
        Cache::put($url_path, $data, 60 * 60);
        return redirect($data['long_url']);
    }

    public function stats(Request $request, $url_path): JsonResponse|Response
    {
        $data = Cache::get($url_path);
        return response()->json([
            'message' => $url_path . ' statistic retrieved successfully',
            'success' => true,
            'data' => $data['stats'],
        ], Response::HTTP_OK);
    }
}
