<?php

namespace App\Services;

use App\Services\NewsProviders\NewsAPIProvider;
use App\Services\NewsProviders\TheGuardianProvider;

class NewsProviderFactory
{

    /**
     * @param string $provider
     * @return NewsAPIProvider|TheGuardianProvider
     */
    public static function make(string $provider)
    {
        return match ($provider) {
            'newsapi' => new NewsAPIProvider(),
            'the_guardian' => new TheGuardianProvider(),
        };
    }
}
