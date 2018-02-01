<?php

if (!function_exists('cache_mod')) {
    /**
     * @return \Helldar\Cache\CacheService
     */
    function cache_mod()
    {
        return new \Helldar\Cache\CacheService();
    }
}
