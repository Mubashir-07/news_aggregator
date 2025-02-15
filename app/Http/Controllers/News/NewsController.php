<?php

namespace App\Http\Controllers\News;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchArticlesRequest;
use App\Http\Requests\FetchPersonalizedNewsRequest;
use App\Http\Requests\FetchTopHeadlineRequest;
use App\Services\NewsProviderFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    /**
     * @return JsonResponse
     */
    public function getPersonalizedNews(FetchPersonalizedNewsRequest $request)
    {
        $user = Auth::user();
        $preferences = $user->preference;

        if (!$preferences || empty($preferences->provider))
            return ApiResponseHelper::error('No preferred providers set.', []);

        $provider = $preferences->provider;
        $newsService = NewsProviderFactory::make($provider);

        $params = [
            'q' => $preferences->keyword,
            'sources' => $preferences->source,
            'page' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        return ApiResponseHelper::success('Articles fetched with preferences successfully', [
            $newsService->fetchArticles($params)
        ], 201);
    }
}
