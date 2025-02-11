<?php

namespace App\Services\NewsProviders;

use App\Interfaces\NewsProviderInterface;

class NewsAPIProvider implements NewsProviderInterface
{
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

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: ' . config('app.name', 'News Aggregator'),
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return json_decode($response, true)['articles'];
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

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'User-Agent: ' . config('app.name', 'News Aggregator'),
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true)['articles'];
    }
}
