<?php

namespace App\Interfaces;

interface NewsProviderInterface
{
    public function fetchArticles(array $params): array;

    public function fetchTopHeadlines(array $params): array;
}
