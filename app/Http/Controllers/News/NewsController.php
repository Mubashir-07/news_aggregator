<?php

namespace App\Http\Controllers\News;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchArticlesRequest;
use App\Http\Requests\FetchTopHeadlineRequest;
use App\Services\NewsProviderFactory;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    /**
     * @param FetchArticlesRequest $request
     * @return JsonResponse
     */
    public function getArticles(FetchArticlesRequest $request)
    {
        $provider = $request->input('provider', 'news_api');
        $validProviders = ['news_api', 'the_guardian', 'nyt'];
        if (!in_array($provider, $validProviders))
            return ApiResponseHelper::error('Invalid news provider', []);

        $newsService = NewsProviderFactory::make($provider);

        $params = [
            'q' => $request->input('keyword'),
            'from' => $request->input('from_date'),
            'to' => $request->input('to_date'),
            'sources' => $request->input('sources'),
            'page' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        return ApiResponseHelper::success('Articles fetched successfully', [
            $newsService->fetchArticles($params)
        ], 201);
    }

    /**
     * @param FetchTopHeadlineRequest $request
     * @return JsonResponse
     */
    public function getTopHeadlines(FetchTopHeadlineRequest $request)
    {
        $provider = $request->query('provider', 'newsapi');
        $newsService = NewsProviderFactory::make($provider);

        $params = [
            'category' => $request->input('category'),
            'sources' => $request->input('sources'),
            'pageSize' => $request->input('pageSize', 10),
        ];

        return ApiResponseHelper::success('Articles fetched successfully', [
            $newsService->fetchTopHeadlines($params)
        ], 201);
    }
}
