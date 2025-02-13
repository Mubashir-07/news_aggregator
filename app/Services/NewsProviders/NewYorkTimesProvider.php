<?php

namespace App\Services\NewsProviders;

use App\Interfaces\NewsProviderInterface;
use App\Traits\MakeApiCall;

class NewYorkTimesProvider implements NewsProviderInterface
{
    use MakeApiCall;

    protected mixed $baseUrl;
    protected mixed $apiKey;

    /**
     *
     */
    public function __construct()
    {
        $this->apiKey = config('services.nyt.key');
        $this->baseUrl = config('services.nyt.base_url');
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchTopHeadlines(array $params): array
    {
        return $this->fetchArticles($params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchArticles(array $params): array
    {
        $section = $params['category'] ?? 'world';
        $url = "{$this->baseUrl}/topstories/v2/{$section}.json?api-key={$this->apiKey}";

        $response = ($this->makeRequest($url));
        $data = json_decode($response, true);

        return $data['results'] ?? [];
    }
}
