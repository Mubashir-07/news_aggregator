<?php

namespace App\Services\NewsProviders;

use App\Interfaces\NewsProviderInterface;
use App\Traits\MakeApiCall;

class TheGuardianProvider implements NewsProviderInterface
{
    use MakeApiCall;

    protected mixed $baseUrl;
    protected mixed $apiKey;

    /**
     *
     */
    public function __construct()
    {
        $this->apiKey = config('services.the_guardian.key');
        $this->baseUrl = config('services.the_guardian.base_url');
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchArticles(array $params): array
    {
        $url = $this->baseUrl . 'search?' . http_build_query(array_merge($params, [
                'api-key' => $this->apiKey,
                'show-fields' => 'headline,byline,thumbnail,body'
            ]));

        $response = ($this->makeRequest($url));
        $data = json_decode($response, true);
        return $data['response']['results'] ?? [];
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchTopHeadlines(array $params): array
    {
        $url = $this->baseUrl . 'search?' . http_build_query(array_merge($params, [
                'api-key' => $this->apiKey,
                'order-by' => 'newest',
                'show-fields' => 'headline,byline,thumbnail,body'
            ]));

        $response = ($this->makeRequest($url));
        $data = json_decode($response, true);
        return $data['response']['results'] ?? [];
    }
}
