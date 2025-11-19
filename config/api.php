<?php 

return [
    'bigquery_url' => env('APP_ENV') === 'production'
        ? env('BIGQUERY_API_URL_LIVE')
        : env('BIGQUERY_API_URL_LOCAL'),

    'bigquery_count_url' => env('APP_ENV') == 'production'
        ? env('BIGQUERY_API_COUNT_URL_LIVE')
        : env('BIGQUERY_API_COUNT_URL_LOCAL'),
    
];
