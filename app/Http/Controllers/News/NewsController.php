<?php

namespace App\Http\Controllers\News;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchArticlesRequest;
use App\Http\Requests\FetchPersonalizedNewsRequest;
use App\Http\Requests\FetchTopHeadlineRequest;
use App\Services\NewsProviderFactory;
use App\Traits\StoringSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    use StoringSchedule;

    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Fetch articles from news providers",
     *     tags={"Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="provider",
     *         in="query",
     *         description="News provider (news_api, the_guardian, nyt)",
     *         required=false,
     *         @OA\Schema(type="string", default="news_api")
     *     ),
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Keyword to search articles",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from_date",
     *         in="query",
     *         description="Start date for filtering articles (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to_date",
     *         in="query",
     *         description="End date for filtering articles (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="sources",
     *         in="query",
     *         description="Comma-separated list of news sources",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         description="Number of articles per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles fetched successfully from cache",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Articles fetched successfully from API",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid news provider")
     * )
     */
    public function getArticles(FetchArticlesRequest $request)
    {
        $provider = $request->input('provider', 'news_api');
        $context = 'fetchArticles';
        $validProviders = ['news_api', 'the_guardian', 'nyt'];
        if (!in_array($provider, $validProviders))
            return ApiResponseHelper::error('Invalid news provider', []);

        $cachedProviderData = Cache::get($provider, []);
        if (isset($cachedProviderData[$context]))
            return ApiResponseHelper::success('Articles fetched successfully', [$cachedProviderData[$context]], 200);

        $newsService = NewsProviderFactory::make($provider);

        $params = [
            'q' => $request->input('keyword'),
            'from' => $request->input('from_date'),
            'to' => $request->input('to_date'),
            'sources' => $request->input('sources'),
            'page' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $articles = $newsService->fetchArticles($params);

        $cachedProviderData[$context] = $articles;
        Cache::put($provider, $cachedProviderData, now()->addMinutes(10));
        $this->scheduleCacheClear($provider, $context, $articles);

        return ApiResponseHelper::success('Articles fetched successfully', [$articles], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/top-headlines",
     *     summary="Fetch top headlines from news providers",
     *     tags={"Headlines"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="provider",
     *         in="query",
     *         description="News provider (news_api, the_guardian, nyt)",
     *         required=false,
     *         @OA\Schema(type="string", default="news_api")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Category of news (e.g., business, sports, technology)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sources",
     *         in="query",
     *         description="Comma-separated list of news sources",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         description="Number of headlines per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Headlines fetched successfully from cache",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Headlines fetched successfully from API",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid news provider")
     * )
     */
    public function getTopHeadlines(FetchTopHeadlineRequest $request)
    {
        $provider = $request->query('provider', 'newsapi');
        $context = 'fetchTopHeadlines';
        $validProviders = ['news_api', 'the_guardian', 'nyt'];

        if (!in_array($provider, $validProviders))
            return ApiResponseHelper::error('Invalid news provider', []);

        $cachedProviderData = Cache::get($provider, []);
        if (isset($cachedProviderData[$context]))
            return ApiResponseHelper::success('Headlines fetched successfully', [$cachedProviderData[$context]], 200);

        $newsService = NewsProviderFactory::make($provider);

        $params = [
            'category' => $request->input('category'),
            'sources' => $request->input('sources'),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $headlines = $newsService->fetchTopHeadlines($params);

        $cachedProviderData[$context] = $headlines;
        Cache::put($provider, $cachedProviderData, now()->addMinutes(10));
        $this->scheduleCacheClear($provider, $context, $headlines);

        return ApiResponseHelper::success('Headlines fetched successfully', [$headlines], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/personalized-news",
     *     summary="Fetch personalized news based on user preferences",
     *     tags={"Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         in="query",
     *         description="Number of articles per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Articles fetched from cache",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Articles fetched from API",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No preferred providers set",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No preferred providers set.")
     *         )
     *     )
     * )
     */
    public function getPersonalizedNews(FetchPersonalizedNewsRequest $request)
    {
        $user = Auth::user();
        $preferences = $user->preference;

        if (!$preferences || empty($preferences->provider))
            return ApiResponseHelper::error('No preferred providers set.', []);

        $provider = $preferences->provider;
        $context = 'fetchPersonalizedNews';
        $cachedProviderData = Cache::get($provider, []);

        if (isset($cachedProviderData[$context]))
            return ApiResponseHelper::success('Articles fetched with preferences successfully', [$cachedProviderData[$context]], 200);

        $newsService = NewsProviderFactory::make($provider);

        $params = [
            'q' => $preferences->keyword,
            'sources' => $preferences->source,
            'page' => $request->input('page', 1),
            'pageSize' => $request->input('pageSize', 10),
        ];

        $articles = $newsService->fetchArticles($params);
        $cachedProviderData[$context] = $articles;
        Cache::put($provider, $cachedProviderData, now()->addMinutes(10));
        $this->scheduleCacheClear($provider, $context, $articles);

        return ApiResponseHelper::success('Articles fetched with preferences successfully', [$articles], 201);
    }
}
