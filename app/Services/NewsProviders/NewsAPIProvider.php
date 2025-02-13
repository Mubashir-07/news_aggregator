<?php

namespace App\Services\NewsProviders;

use App\Interfaces\NewsProviderInterface;
use App\Traits\MakeApiCall;

class NewsAPIProvider implements NewsProviderInterface
{
    use MakeApiCall;

    protected mixed $baseUrl;
    protected mixed $apiKey;

    /**
     *
     */
    public function __construct()
    {
        $this->apiKey = config('services.news_api.key');
        $this->baseUrl = config('services.news_api.base_url');
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchArticles(array $params): array
    {
        $url = $this->baseUrl . 'everything?' . http_build_query(array_merge($params, [
                'apiKey' => $this->apiKey,
            ]));

        $response = ($this->makeRequest($url));
        $data = json_decode($response, true);

        return $data['articles'] ?? [];
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchTopHeadlines(array $params): array
    {
        $url = $this->baseUrl . 'top-headlines?' . http_build_query(array_merge($params, [
                'apiKey' => $this->apiKey,
            ]));

        $response = ($this->makeRequest($url));
        $data = json_decode($response, true);

        return $data['articles'] ?? [];
    }
}
