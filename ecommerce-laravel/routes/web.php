<?php

use Illuminate\Support\Facades\Cache;

Route::get('/test-redis', function () {
    Cache::put('test_key', 'Redis is working!', 10); // Cache for 10 seconds
    return Cache::get('test_key');
});
