<?php

namespace Tests\Unit;

use App\Http\Requests\UrlInputRequest;
use App\Services\UrlShortenerService as UrlShortener;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class UrlShortenerTest extends TestCase
{
    /**
     * A basic unit test example.
     * @test
     */
    public function test_should_assert_that_short_link_was_created(): void
    {
        $currentDate = Carbon::now();
        $request = new UrlInputRequest();
        $request->merge(['url' => 'https://indicina.co']);
        $urlShortener = new UrlShortener($request);
        Cache::shouldReceive('has')->andReturn(true);
        Cache::shouldReceive('get')->andReturn([
            'long_url' => 'https://indicina.co',
            'short_url' => 'http://localhost:8000/' . $urlShortener->id,
            'url_path' => $urlShortener->id,
            'statistic' => [
                'click_count' => 0,
                'last_accessed' => null,
                'date_time_accessed' => [],
                'device_info' => [
                    'ip_address' => [],
                    "browser" => [],
                ]
            ],
            'created_at' => $currentDate,
            'updated_at' => $currentDate
        ]);
        $this->assertTrue($urlShortener->checkIfShortLinkExists());
        $this->assertIsArray($urlShortener->getShortLinkData());
        $this->assertArrayHasKey('long_url', $urlShortener->getShortLinkData());
        $this->assertArrayHasKey('short_url', $urlShortener->getShortLinkData());
        $this->assertArrayHasKey('url_path', $urlShortener->getShortLinkData());
        $this->assertArrayHasKey('statistic', $urlShortener->getShortLinkData());
        $this->assertArrayHasKey('click_count', $urlShortener->getShortLinkData()['statistic']);
        $this->assertArrayHasKey('last_accessed', $urlShortener->getShortLinkData()['statistic']);
        $this->assertArrayHasKey('date_time_accessed', $urlShortener->getShortLinkData()['statistic']);
        $this->assertArrayHasKey('device_info', $urlShortener->getShortLinkData()['statistic']);
        $this->assertArrayHasKey('ip_address', $urlShortener->getShortLinkData()['statistic']['device_info']);
        $this->assertArrayHasKey('browser', $urlShortener->getShortLinkData()['statistic']['device_info']);
        $this->assertArrayHasKey('created_at', $urlShortener->getShortLinkData());
        $this->assertArrayHasKey('updated_at', $urlShortener->getShortLinkData());
    }
}
