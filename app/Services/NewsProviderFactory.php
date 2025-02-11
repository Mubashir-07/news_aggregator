<?php

namespace App\Services;

use App\Services\NewsProviders\NewsAPIProvider;
use App\Services\NewsProviders\NewsCredProvider;

class NewsProviderFactory
{
    /**
     * @param string $provider
     * @return NewsAPIProvider|NewsCredProvider
     */
    public static function make(string $provider)
    {
        return match ($provider) {
            'newsapi' => new NewsAPIProvider(),
            'newscred' => new NewsCredProvider(),
        };
    }
}
