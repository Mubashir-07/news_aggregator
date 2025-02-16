<?php

namespace App\Http\Controllers\News;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Models\UserPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/preferences",
     *     summary="Store or update user preferences",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"provider"},
     *             @OA\Property(property="provider", type="string", example="news_api"),
     *             @OA\Property(property="keyword", type="string", example="technology"),
     *             @OA\Property(property="source", type="string", example="bbc-news")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Preferences saved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="provider", type="string", example="news_api"),
     *                 @OA\Property(property="keyword", type="string", example="technology"),
     *                 @OA\Property(property="source", type="string", example="bbc-news")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The provider field is required.")
     *         )
     *     )
     * )
     */
    public function store(UserPreferenceRequest $request)
    {
        $user = Auth::user();
        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'provider',
                'keyword',
                'source',
            ])
        );

        return ApiResponseHelper::success('Preferences saved successfully', $preferences, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/preferences",
     *     summary="Get user preferences",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Preferences fetched successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="provider", type="string", example="news_api"),
     *                 @OA\Property(property="keyword", type="string", example="technology"),
     *                 @OA\Property(property="source", type="string", example="bbc-news")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No preferences found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No preferences found.")
     *         )
     *     )
     * )
     */
    public function show()
    {
        $preferences = Auth::user()->preference;

        if (!$preferences)
            return ApiResponseHelper::error('No preferences found.', []);

        return ApiResponseHelper::success('Preferences fetched successfully', $preferences, 201);
    }
}
