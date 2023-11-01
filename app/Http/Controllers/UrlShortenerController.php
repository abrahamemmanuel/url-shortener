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
use App\Traits\CustomHttpResponseTrait;
use Illuminate\Support\Facades\Cache;

class UrlShortenerController extends Controller implements UrlShortenerInterface
{
    use CustomHttpResponseTrait;

    public function encode(UrlInputRequest $request): JsonResponse|Response
    {
        return $this->customSuccessMessageHandler(
            'Short Url created successfully', 
            (new UrlShortener($request))->createShortLink(), 
            Response::HTTP_CREATED
        );
    }

    public function decode(UrlInputRequest $request): JsonResponse|Response
    {
        $data = new UrlShortener($request);
        return !$data->checkIfShortLinkExists() 
        ?
        $this->customExceptionMessageHandler()
        :
        $this->customSuccessMessageHandler(
            $request->input('url') . ' decoded successfully',
            $data->getShortLinkData()['long_url'], 
            Response::HTTP_OK
        );
    
    }

    public function redirect(Request $request, $url_path): JsonResponse|RedirectResponse
    {
        $request->merge(['url' => $url_path]);
        $data = new UrlShortener($request);

        return !$data->checkIfShortLinkExists() 
        ?
        $this->customExceptionMessageHandler()
        :
        redirect()->away($data->updateShortLinkStatistic());
    }

    public function statistic(Request $request, $url_path): JsonResponse|Response
    {
        $request->merge(['url' => $url_path]);
        $data = new UrlShortener($request);

        return !$data->checkIfShortLinkExists() 
        ?
        $this->customExceptionMessageHandler()
        :
        $this->customSuccessMessageHandler(
            $request->input('url') . ' statistic fetched successfully',
            $data->getShortLinkData()['statistic'], 
            Response::HTTP_OK
        );
    }
}
