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
     * @param UserPreferenceRequest $request
     * @return JsonResponse
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
     * @return JsonResponse
     */
    public function show()
    {
        $preferences = Auth::user()->preference;

        if (!$preferences)
            return ApiResponseHelper::error('No preferences found.', []);

        return ApiResponseHelper::success('Preferences fetched successfully', $preferences, 201);
    }
}
