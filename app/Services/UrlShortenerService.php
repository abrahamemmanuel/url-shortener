<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UrlShortenerRepository;
use PUGX\Shortid\Shortid;
use Illuminate\Support\Facades\Cache;

class UrlShortenerService
{
    protected string $url_path;
    protected string $long_url;
    protected string $short_url;
    protected string $base_url;
    protected object $id;
    protected bool $is_found;
    
    public function __construct(Request $request = null, string $url_path = "")
    {
        
        $this->id = Shortid::generate();
        $this->long_url = $request->input('url') ?? null;
        $this->url_path = explode('/', $request->input('url'))[3] ?? $url_path;
        $this->base_url = $request->root() ?? null;
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
        Cache::put($this->id, $this->setShortLinkData(), 60 * 60);
        return Cache::get($this->id);
    }

    public function updateShortLink(): array | JsonResponse
    {
        return Cache::put($this->url_path, $this->updateStatisticData(), 60 * 60);
 
    }

    public function updateStatisticData(): array
    {
        $data = $this->getShortLinkData();
        $data['statistic']['click_count'] += 1;
        $data['statistic']['last_accessed'] = now();
        $data['statistic']['date_time_accessed'][] = now();
        $data['statistic']['referrer'][] = $_SERVER['HTTP_REFERER'];
        $data['statistic']['geographic']['country'][] = $_SERVER['HTTP_CF_IPCOUNTRY'];
        $data['statistic']['geographic']['city'][] = $_SERVER['HTTP_CF_IPCITY'];
        $data['statistic']['device_info'][] = $_SERVER['HTTP_USER_AGENT'];
        return $data;
    }
}