<?php

namespace App\Services;

use App\Services\NewsProviders\NewsAPIProvider;
use App\Services\NewsProviders\NewYorkTimesProvider;
use App\Services\NewsProviders\TheGuardianProvider;

class NewsProviderFactory
{

    /**
     * @param string $provider
     * @return NewsAPIProvider|NewYorkTimesProvider|TheGuardianProvider
     */
    public static function make(string $provider)
    {
        return match ($provider) {
            'news_api' => new NewsAPIProvider(),
            'the_guardian' => new TheGuardianProvider(),
            'nyt' => new NewYorkTimesProvider(),
        };
    }
}
