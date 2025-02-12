<?php

namespace App\Services\NewsProviders;

use App\Interfaces\NewsProviderInterface;

class TheGuardianProvider implements NewsProviderInterface
{
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

        return $this->makeRequest($url);
    }

    /**
     * @param $url
     * @return mixed
     */
    function makeRequest($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'User-Agent: ' . config('app.name', 'News Aggregator'),
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true)['response']['results'];
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

        return $this->makeRequest($url);
    }
}
