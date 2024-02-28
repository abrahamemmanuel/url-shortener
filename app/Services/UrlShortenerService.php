<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use PUGX\Shortid\Shortid;
use Illuminate\Support\Facades\Cache;
use App\Models\ShortUrl;

class UrlShortenerService
{
    protected string $url_path;
    protected string $long_url;
    protected string $short_url;
    protected string $base_url;
    public object $id;
    protected bool $is_found;
    
    public function __construct(Request $request)
    {
        $this->id = Shortid::generate();
        $this->long_url = $request->input('url') ?? "";
        $this->url_path =  explode('/', $request->input('url'))[3] ?? $request->input('url');
        $this->base_url = $request->root() ?? "";
        $this->short_url = $this->base_url . '/' . $this->id ?? null;
    }

    public function setShortLinkData(): array
    {
        return [
            'long_url' => $this->long_url,
            'short_url' => $this->short_url,
            'url_path' => $this->id,
            'statistic' => [
                'click_count' => 0,
                'last_accessed' => null,
                'date_time_accessed' => [],
                'device_info' => [
                    'ip_address' => [],
                    "browser" => [],
                ]
            ],
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function getShortLinkData(): array
    {
        return Cache::get($this->url_path);
    }

    public function checkIfShortLinkExists(): bool
    {
        return Cache::has($this->url_path);
    }

    public function createShortLink(): array
    {
        $this->saveToDB();
        Cache::put($this->id, $this->setShortLinkData(), 24 * 60 * 60);
        return Cache::get($this->id);
    }

    public function updateShortLinkStatistic(): string
    {
        Cache::put($this->url_path, $this->updateStatisticData(), 24 * 60 * 60);
        return $this->getShortLinkData()['long_url'];
    }

    public function updateStatisticData(): array
    {
        $data = $this->getShortLinkData();
        $data['statistic']['click_count'] += 1;
        $data['statistic']['last_accessed'] = now();
        $data['statistic']['date_time_accessed'][] = now();
        $data['statistic']['device_info']['ip_address'][] = request()->ip();
        $data['statistic']['device_info']['browser'][] = request()->userAgent();
        return $data;
    }

    public function saveToDB(): ShortUrl
    {
        $short_url = new ShortUrl();
        $short_url->long_url = $this->long_url;
        $short_url->short_url = $this->short_url;
        $short_url->url_path = $this->id;
        $short_url->save();
        return $short_url;
    }
}