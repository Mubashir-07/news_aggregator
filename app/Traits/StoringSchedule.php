<?php

namespace App\Traits;

use App\Models\News;
use Illuminate\Support\Facades\Cache;

trait StoringSchedule
{

    /**
     * @param $provider
     * @param $context
     * @param $articles
     * @return void
     */
    public function scheduleCacheClear($provider, $context, $articles)
    {
        dispatch(function () use ($provider, $context, $articles) {
            sleep(600);

            News::create([
                'provider' => $provider,
                'context' => $context,
                'data' => json_encode($articles),
                'updated_at' => now(),
            ]);

            $cachedProviderData = Cache::get($provider, []);
            unset($cachedProviderData[$context]);

            if (empty($cachedProviderData))
                Cache::forget($provider);
            else
                Cache::put($provider, $cachedProviderData, now()->addMinutes(10));

        })->onQueue('cache_cleanup');
    }
}
