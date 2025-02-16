# news_aggregator
Introduction 
<br>
The News Aggregator API fetches and consolidates news articles from multiple sources, including NewsAPI, The Guardian, and The New York Times. It provides RESTful endpoints for retrieving news articles, top headlines, and user preferences.

Setup Instructions <br>

Prerequisites<br>

PHP 8.1 or later<br>

Composer<br>

Laravel 11<br>

MySQL (or any other database supported by Laravel)<br>

Redis (for caching, optional)<br>

Installation <br>

1 - Clone the repository:
git clone https://github.com/your-username/news-aggregator.git
<br>
2 - Install dependencies: <br>
composer install
<br>
3 - Copy the environment file and configure database and API keys:
<br>
cp .env.example .env
<br>
4 - Update database credentials (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD).
<br>
5 - Add API keys for news sources in the .env file:
<br>
NEWS_API_KEY=your_newsapi_key <br>

GUARDIAN_API_KEY=your_guardian_key <br>

NYT_API_KEY=your_nyt_key <br>

6 - Generate application key: php artisan key:generate <br>
7 - Run migrations: php artisan migrate
8 - (Optional) Seed the database: php artisan db:seed<br>
9 - Start the development server: php artisan serve


<br>

API Documentation
<br>
The API is documented using Swagger. You can access the documentation at: http://your-local-url/docs


Features <br>
Fetch latest news from multiple providers. <br>
Dynamic provider switching. <br>
User preferences for news filtering. <br>
Authentication using Laravel Sanctum. <br>
Cached responses for optimized performance. <br> <br>
Additional Notes  <br>
Ensure API keys are valid, as some providers have request limits. <br>
Redis caching is recommended for performance. <br>
Queue workers should be configured to handle scheduled cache clearing. <br>








