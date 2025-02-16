<?php

namespace App\Interfaces;

/**
 * Interface NewsProviderInterface
 *
 * Defines the contract for news provider integrations.
 */
interface NewsProviderInterface
{
    public function fetchArticles(array $params): array;

    public function fetchTopHeadlines(array $params): array;
}
