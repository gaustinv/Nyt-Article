<?php

return [
    'database' => [
        'driver' => 'sqlite',
        'database_path' => '../../../database/database.sqlite',  // Path to the SQLite database file
    ],

    'nyt_api' => [
        'api_key' => 'ymepacKP8hRKCPGpezet6iq2Obj3WAZT',  // Replace with your actual API key
        'base_url' => 'https://api.nytimes.com/svc/search/v2/articlesearch.json',
    ],

    'jwt' => [
        'secret_key' => 'ltXg/HI3GvMWkhGW9GgM5uEZ8TQ3DAxMe9Ey7uvbfPY=',  // Replace with a secure, random key              // Issuer of the token
        'audience' => 'public',         // Audience for the token
        'expiration_time' => 3600,             // Token expiration time in seconds (1 hour)
    ],

    'rate_limiter' => [
        'requests_per_interval' => 5,  // Maximum requests
        'interval_seconds' => 300,     // Interval in seconds (5 minutes)
    ] 
];
